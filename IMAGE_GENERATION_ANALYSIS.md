# Image Generation Analysis: /cms2, /demo, /learn

## Working Model Summary

The `/cms2` image generation uses the **AppController** from `/learn/backup/AppController.php`, which implements a **6-step fallback pipeline**:

### Pipeline Steps

1. **Generate Answer** (Groq API)
   - Input: User question/topic
   - Output: Detailed AI-generated answer (500-750 words)
   - Timeout: 30s
   - Error handling: Returns JSON error on cURL failure

2. **Summarize Answer** (Groq llama-3.1-8b-instant)
   - Input: Full answer (truncated to 800 chars)
   - Output: 1-2 sentence summary focusing on core topic
   - Timeout: 8s
   - Fallback: Returns empty string on failure
   - Why: Full answer contains examples/caveats that confuse image generation

3. **Convert Summary to Image Prompt** (Groq llama-3.1-8b-instant)
   - Input: Summary text
   - Output: 8-15 word descriptive visual prompt (e.g., "pyramid with DSA concepts at base and AI at top")
   - Timeout: 8s
   - Validation: Rejects prompts <5 chars or >200 chars (hallucination detection)
   - Fallback: Empty string triggers keyword extraction

4. **Generate Image via Pollinations AI** (Free, no API key)
   - Input: Image prompt
   - Output: Full Pollinations URL with deterministic seed
   - Method: GET request to `https://image.pollinations.ai/prompt/{encoded_prompt}`
   - Features: 2048x1260 resolution, quality enhancement, no watermark
   - No timeout (URL construction only)

5. **Fallback to Wikimedia Commons** (If Pollinations fails)
   - Input: Image prompt (used as search keywords)
   - Output: Wikimedia Commons image URL (1024px thumbnail)
   - Timeout: 5s
   - User-Agent: Required by Wikimedia API
   - Filtering: Only accepts JPEG/PNG/WebP (skips PDFs, SVGs)

6. **Final Fallback to Placeholder** (If all else fails)
   - Input: Image prompt (first 50 chars)
   - Output: `https://placehold.co/1024x630?text={prompt}`
   - Ensures user always sees something

---

## Constraints Analysis

### Input Size
- Question/topic: No hard limit (used as-is)
- Answer: Truncated to 800 chars for summarization
- Summary: Expected 1-2 sentences (~100-200 chars)
- Image prompt: Validated to 5-200 chars

### Concurrency
- Each step is sequential (no parallel API calls)
- Rate limiting: 10 requests per 60 seconds per session (in /cms2/ajax.php)
- Timeouts prevent hanging: 30s (answer), 8s (summarize/prompt), 5s (Wikimedia)

### Failure Possibility
- **Groq API**: Rate limit, invalid key, network timeout → fallback to keyword extraction
- **Summarization timeout**: Returns empty → keyword extraction
- **Prompt generation timeout**: Returns empty → keyword extraction
- **Pollinations**: Returns broken URL or empty → Wikimedia fallback
- **Wikimedia**: Returns empty → placeholder fallback
- **All failures**: Placeholder always succeeds

---

## Data Structure & Algorithm

**DS&A (one line):** Multi-stage LLM pipeline with sequential fallbacks: answer → summarize → prompt → Pollinations → Wikimedia → placeholder.

**Why not brute force:** Each step is optimized for its purpose (Groq for text, Pollinations for generation, Wikimedia for search). Fallbacks are ordered by quality (Pollinations > Wikimedia > placeholder).

---

## Implementation Rules

✅ **Minimal change only** — Diagnostic logging added, no algorithm changes
✅ **Code commented for humans (WHY, not WHAT)** — Comments explain purpose of each step
✅ **Explicit exception handling** — All cURL errors caught, timeouts set, JSON validation
✅ **Meaningful error messages** — Logs include step number, input, output, failure reason
✅ **Secure against injection** — URL encoding, prepared statements, input validation

---

## Database Check

**Not applicable** — Image generation is stateless. Images are stored in `lessons.image_url` column after generation.

---

## Automated Tests (STRICT ORDER)

### 1. Edge / Invalid Cases
```php
// Test 1.1: Empty question
$result = $app->generateAnswerAndImage('', '');
// Expected: Placeholder (empty prompt → keyword extraction → empty → placeholder)

// Test 1.2: Very long question (>5000 chars)
$longQ = str_repeat('What is AI? ', 500);
$result = $app->generateAnswerAndImage($longQ, $longQ);
// Expected: Valid image URL (should handle gracefully)

// Test 1.3: Special characters in question
$result = $app->generateAnswerAndImage('What is "AI" & machine learning?', '...');
// Expected: Valid image URL (URL encoding should handle)
```

### 2. Boundary Cases
```php
// Test 2.1: Exactly 800 chars (summarization truncation boundary)
$answer = str_repeat('a', 800);
$summary = $app->summarizeAnswer($answer);
// Expected: Non-empty summary (should not be truncated)

// Test 2.2: Prompt exactly 5 chars (minimum validation)
// (Internal: test answerToImagePrompt with 5-char output)
// Expected: Accepted (>= 5)

// Test 2.3: Prompt exactly 200 chars (maximum validation)
// (Internal: test answerToImagePrompt with 200-char output)
// Expected: Accepted (<= 200)

// Test 2.4: Prompt 201 chars (exceeds max)
// (Internal: test answerToImagePrompt with 201-char output)
// Expected: Rejected, fallback to keyword extraction
```

### 3. Negative / Security Cases
```php
// Test 3.1: SQL injection in question
$result = $app->generateAnswerAndImage("'; DROP TABLE lessons; --", '...');
// Expected: Safe (no DB queries in image generation)

// Test 3.2: XSS payload in question
$result = $app->generateAnswerAndImage('<script>alert("xss")</script>', '...');
// Expected: URL-encoded in Pollinations URL, safe

// Test 3.3: Groq API timeout (simulate with 1ms timeout)
// (Internal: test with CURLOPT_TIMEOUT = 0.001)
// Expected: Falls back to keyword extraction

// Test 3.4: Wikimedia API timeout
// (Internal: test searchWikimediaImage with 1ms timeout)
// Expected: Returns empty, triggers placeholder fallback
```

### 4. Valid Functional Cases
```php
// Test 4.1: Standard question (working case from test)
$result = $app->generateAnswerAndImage(
    'How Important Is Data Structure and Algorithms in the Era of Gen AI?',
    'How Important Is Data Structure and Algorithms in the Era of Gen AI?'
);
// Expected: 
// - Answer: 3000+ chars, no error
// - Image: Pollinations URL with "pyramid DSA" prompt

// Test 4.2: Simple question
$result = $app->generateAnswerAndImage('What is photosynthesis?', 'What is photosynthesis?');
// Expected:
// - Answer: Valid biology explanation
// - Image: Relevant photosynthesis diagram

// Test 4.3: Deepen operation (used in /cms2)
$deeperPrompt = "Here I already asked: What is AI? ... Make this deeper...";
$result = $app->generateAnswerAndImage($deeperPrompt, 'What is AI?');
// Expected:
// - Answer: More detailed, references previous answer
// - Image: Still relevant to core topic (AI)

// Test 4.4: Image regeneration (uses generateImage only)
$result = $app->generateImage('Data structures and algorithms');
// Expected:
// - Image: Wikimedia or placeholder (no summarization)
// - Faster than generateAnswerAndImage (no Groq calls)
```

---

## Complexity

- **Time: O(n)** where n = answer length (linear scan for keyword extraction, all API calls are constant-time)
- **Space: O(n)** where n = answer length (stores full answer + summary + prompt in memory)

---

## Warnings / Safe Scope

✅ **Don't touch anything else** — Only modified AppController logging and /cms2 logging
✅ **Don't add/remove code outside scope** — Only added error_log() calls
✅ **Don't modify unrelated files** — Only touched image generation pipeline
✅ **Don't refactor architecture** — Kept 6-step pipeline intact
✅ **Better method applied safely** — Diagnostic logging helps identify root cause without changing logic

---

## Manual Tests (STRICT ORDER)

### Manual Test Props

#### Test 1: Edge Cases
**Guidance:** Test with empty/invalid inputs to ensure graceful fallback

**Data:**
1. Question: `""` (empty string)
   - Expected: Placeholder image with generic text
   - Check logs: Should see `[IMG-GEN-STEP3-FALLBACK]` (keyword extraction)

2. Question: `"a"` (single character)
   - Expected: Placeholder image
   - Check logs: Should see `[IMG-GEN-STEP3-FALLBACK]`

3. Question: `"What is the meaning of life, the universe, and everything? " × 100` (very long)
   - Expected: Valid image (Pollinations or Wikimedia)
   - Check logs: Should see `[IMG-GEN-STEP1]` with answer length > 3000

---

#### Test 2: Boundary Cases
**Guidance:** Test at limits of validation (prompt length, timeout, etc.)

**Data:**
1. Question: `"photosynthesis"` (short, single word)
   - Expected: Relevant plant/leaf image
   - Check logs: Should see `[IMG-GEN-STEP3]` with short prompt

2. Question: `"Data structures and algorithms in artificial intelligence"` (medium, 8 words)
   - Expected: Relevant diagram image
   - Check logs: Should see `[IMG-GEN-STEP3]` with descriptive prompt

3. Question: Same as screenshot: `"How Important Is Data Structure and Algorithms in the Era of Gen AI?"`
   - Expected: Pyramid/DSA diagram (from test run)
   - Check logs: Should see `[IMG-GEN-STEP3]` with "pyramid" prompt

---

#### Test 3: Negative / Failure Cases
**Guidance:** Test graceful degradation when APIs fail

**Data:**
1. Simulate Groq timeout: Manually edit AppController to set `CURLOPT_TIMEOUT = 0.001`
   - Expected: Falls back to keyword extraction, image still generated
   - Check logs: Should see `[IMG-GEN-STEP3-FALLBACK]`

2. Simulate Wikimedia failure: Manually edit searchWikimediaImage to return empty
   - Expected: Falls back to placeholder
   - Check logs: Should see `[IMG-GEN-STEP6]` with placehold.co URL

---

#### Test 4: Security Cases
**Guidance:** Ensure injection attacks are neutralized

**Data:**
1. Question: `"'; DROP TABLE lessons; --"`
   - Expected: Safe image generation (no DB queries in pipeline)
   - Check logs: Should see normal pipeline steps

2. Question: `"<script>alert('xss')</script>"`
   - Expected: URL-encoded in Pollinations URL
   - Check logs: Should see `[IMG-GEN-STEP4]` with encoded URL

---

#### Test 5: Functional Validation
**Guidance:** Verify complete pipeline works end-to-end

**Data:**
1. Create new lesson in /cms2 with question: `"Explain machine learning to a 10-year-old"`
   - Expected: 
     - Answer: Simple, age-appropriate explanation
     - Image: Relevant ML diagram or concept illustration
   - Check: Image displays correctly in right panel

2. Create new lesson with question: `"What are the benefits of renewable energy?"`
   - Expected:
     - Answer: Detailed explanation of solar, wind, hydro, etc.
     - Image: Renewable energy diagram or installation photo
   - Check: Image matches topic (not generic "people working")

3. Use "Regenerate Image" button on existing lesson
   - Expected: New image generated for same topic
   - Check logs: Should see `[IMG-GEN-STEP4]` with different seed

---

#### Test 6: Logical Validation (Requirements-Driven)
**Guidance:** Verify behavior matches documented requirements

**Data:**
1. Verify summarization improves image relevance:
   - Question: `"What is the capital of France and why is it important for European politics and trade?"`
   - Expected: Image shows Paris/France (not generic "politics")
   - Why: Summarization extracts "France capital" from noisy question
   - Check logs: Should see `[IMG-GEN-STEP2]` with summary containing "Paris" or "France"

2. Verify fallback chain works:
   - Create lesson, check logs for which source was used (Pollinations > Wikimedia > placeholder)
   - Expected: Most lessons use Pollinations (fastest, best quality)
   - Some may use Wikimedia (if Pollinations fails)
   - Rarely use placeholder (only if both fail)

3. Verify rate limiting prevents abuse:
   - Rapidly click "Generate Lesson" 11 times in 60 seconds
   - Expected: 11th request fails with "Too many requests" error
   - Check: Rate limit enforced in /cms2/ajax.php (line 72-90)

---

## Stop Condition

All requirements are clear. Implementation is ready for testing.

