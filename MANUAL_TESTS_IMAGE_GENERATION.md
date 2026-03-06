# Manual Tests for Image Generation Fix

## Overview
This document provides manual test cases with specific data props to validate the image generation fix in `/cms2`.

**Test Environment:** Local XAMPP server at `http://localhost/wheelder/lesson/cms2`

---

## Test 1: Edge Cases

### 1.1 Empty Question
**Guidance:** Test that empty input gracefully falls back to placeholder

**Data:**
- Question: `` (empty string)
- Expected behavior: System should generate placeholder image with generic text
- Expected image: `https://placehold.co/1024x630?text=...`

**Steps:**
1. Navigate to `/lesson/cms2`
2. Leave question field empty
3. Click "Generate Lesson"
4. Check error message: "Please enter a topic or question."

**Validation:** ✓ Error message prevents empty submission

---

### 1.2 Very Long Question
**Guidance:** Test that long inputs don't cause truncation or corruption

**Data:**
- Question: `"What is artificial intelligence and how does it work in modern applications and what are the implications for society and the future of work and education and healthcare and transportation and finance and entertainment and all other industries?"` (150+ chars)
- Expected behavior: System should handle gracefully, generate answer and image
- Expected image: Valid Pollinations or Wikimedia URL

**Steps:**
1. Navigate to `/lesson/cms2`
2. Paste the long question into the textarea
3. Click "Generate Lesson"
4. Wait for generation to complete
5. Check right panel for image

**Validation:**
- ✓ Answer panel shows detailed response
- ✓ Image panel displays image (not broken/placeholder)
- ✓ Image is relevant to the topic

---

### 1.3 Special Characters
**Guidance:** Test that special characters are safely encoded

**Data:**
- Question: `What is "AI" & machine learning? (2024)`
- Expected behavior: Special characters should be URL-encoded safely
- Expected image: Valid URL with encoded characters

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter question with special characters: `What is "AI" & machine learning?`
3. Click "Generate Lesson"
4. Check browser console (F12) for any JavaScript errors
5. Verify image loads in right panel

**Validation:**
- ✓ No JavaScript errors in console
- ✓ Image URL is properly encoded (no broken characters)
- ✓ Image displays correctly

---

## Test 2: Boundary Cases

### 2.1 Single-Word Question
**Guidance:** Test that short, simple inputs work correctly

**Data:**
- Question: `photosynthesis`
- Expected behavior: Should extract keyword and generate relevant image
- Expected image: Plant/leaf/photosynthesis diagram

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter: `photosynthesis`
3. Click "Generate Lesson"
4. Wait for completion
5. Check image in right panel

**Validation:**
- ✓ Answer explains photosynthesis
- ✓ Image shows plant/leaf/sun (relevant to topic)
- ✓ Image is not generic/unrelated

---

### 2.2 Medium-Length Question (8-10 words)
**Guidance:** Test typical user input length

**Data:**
- Question: `Data structures and algorithms in artificial intelligence`
- Expected behavior: Should generate detailed answer and relevant diagram
- Expected image: Algorithm/data structure/AI diagram

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter: `Data structures and algorithms in artificial intelligence`
3. Click "Generate Lesson"
4. Wait for completion
5. Check image in right panel

**Validation:**
- ✓ Answer discusses DSA and AI
- ✓ Image shows relevant diagram (pyramid, flowchart, or algorithm visualization)
- ✓ Image matches topic (not generic "people working")

---

### 2.3 Question from Screenshot
**Guidance:** Test the exact question from the reported issue

**Data:**
- Question: `How Important Is Data Structure and Algorithms in the Era of Gen AI?`
- Expected behavior: Should generate detailed answer and relevant diagram
- Expected image: DSA/AI diagram (NOT generic people image)

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter: `How Important Is Data Structure and Algorithms in the Era of Gen AI?`
3. Click "Generate Lesson"
4. Wait for completion (30-60 seconds)
5. Check image in right panel
6. Open browser console (F12) and check for error logs starting with `[IMG-GEN-`

**Validation:**
- ✓ Answer discusses importance of DSA in Gen AI era
- ✓ Image shows relevant diagram (NOT generic people/office image)
- ✓ Console logs show pipeline steps: STEP1 → STEP2 → STEP3 → STEP4 (or fallback)
- ✓ Image URL contains either `pollinations.ai` or `wikimedia` or `placehold.co`

---

## Test 3: Negative / Failure Cases

### 3.1 Simulate Network Failure
**Guidance:** Test graceful degradation when APIs fail

**Data:**
- Question: `What is machine learning?`
- Simulate: Disconnect network or block API calls
- Expected behavior: Should fall back to placeholder image

**Steps:**
1. Navigate to `/lesson/cms2`
2. Open browser DevTools (F12) → Network tab
3. Enter question: `What is machine learning?`
4. Click "Generate Lesson"
5. Immediately block network requests (DevTools → Network → Offline)
6. Check right panel for fallback behavior

**Validation:**
- ✓ System doesn't crash
- ✓ Error message appears or placeholder image shown
- ✓ User can still see answer text (if generated before network failure)

---

### 3.2 Image Load Failure
**Guidance:** Test client-side fallback when image URL is broken

**Data:**
- Question: `What is quantum computing?`
- Simulate: Image URL returns 404 or broken image
- Expected behavior: Should fall back to placeholder

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter: `What is quantum computing?`
3. Click "Generate Lesson"
4. Once image loads, open DevTools (F12) → Network tab
5. Find the image request and check its status
6. If image loads successfully, manually break it by editing the URL in console:
   ```javascript
   document.querySelector('.contentImage img').src = 'https://example.com/broken.jpg';
   ```
7. Observe fallback behavior

**Validation:**
- ✓ If image URL is broken, fallback placeholder appears
- ✓ Fallback shows generic text or lesson topic
- ✓ No JavaScript errors in console

---

### 3.3 Image Regeneration Failure
**Guidance:** Test "Regenerate Image" button when it fails

**Data:**
- Question: `What is blockchain?`
- Expected behavior: Retry button should work even if first image fails
- Expected image: Relevant blockchain diagram

**Steps:**
1. Navigate to `/lesson/cms2`
2. Generate a lesson: `What is blockchain?`
3. Once image appears, click the "Regenerate Image" button (↻ icon)
4. Wait for new image to generate
5. Check if new image is different from original

**Validation:**
- ✓ Regenerate button is clickable
- ✓ New image is generated (may be different or same due to deterministic seed)
- ✓ No errors in console
- ✓ Loading spinner appears during regeneration

---

## Test 4: Security Cases

### 4.1 SQL Injection Attempt
**Guidance:** Verify that SQL injection payloads are safe

**Data:**
- Question: `'; DROP TABLE lessons; --`
- Expected behavior: Should be treated as normal text, no DB damage
- Expected image: Placeholder (since question is nonsensical)

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter: `'; DROP TABLE lessons; --`
3. Click "Generate Lesson"
4. Check that system still works normally
5. Verify lessons table still exists (check sidebar)

**Validation:**
- ✓ No database errors
- ✓ Lessons table is intact
- ✓ System generates answer and image normally
- ✓ No security breach

---

### 4.2 XSS Payload Attempt
**Guidance:** Verify that XSS payloads are safely encoded

**Data:**
- Question: `<script>alert('xss')</script>`
- Expected behavior: Should be URL-encoded, no alert() executed
- Expected image: Placeholder

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter: `<script>alert('xss')</script>`
3. Click "Generate Lesson"
4. Check that no alert dialog appears
5. Open DevTools (F12) → Console and check for errors

**Validation:**
- ✓ No alert() dialog appears
- ✓ No JavaScript errors in console
- ✓ Image URL is properly encoded (contains `%3C`, `%3E`, etc.)
- ✓ Answer is generated normally

---

## Test 5: Functional Validation

### 5.1 Complete Workflow
**Guidance:** Test the complete lesson generation workflow

**Data:**
- Question: `Explain machine learning to a 10-year-old`
- Expected behavior: Simple, age-appropriate answer with relevant image
- Expected image: Simple ML diagram or illustration

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter: `Explain machine learning to a 10-year-old`
3. Click "Generate Lesson"
4. Wait for completion
5. Verify answer is simple and age-appropriate
6. Check image is relevant and not too technical

**Validation:**
- ✓ Answer uses simple language
- ✓ Answer includes examples
- ✓ Image is relevant (not generic)
- ✓ Image is displayed in right panel
- ✓ "Deepen / Go Deeper" button appears

---

### 5.2 Deepen Functionality
**Guidance:** Test that deepening works with proper image updates

**Data:**
- Question: `What are renewable energy sources?`
- Deepen: Click "Deepen / Go Deeper" button
- Expected behavior: Answer becomes more detailed, image updates
- Expected image: More detailed renewable energy diagram

**Steps:**
1. Generate lesson: `What are renewable energy sources?`
2. Wait for completion
3. Click "Deepen / Go Deeper" button
4. Wait for deeper answer to generate
5. Check that new image appears in right panel
6. Verify "Depth 1/7" badge appears

**Validation:**
- ✓ Deeper answer is more detailed than original
- ✓ New image is generated
- ✓ Image is still relevant to renewable energy
- ✓ Depth badge shows "Depth 1/7"
- ✓ Can click "Deepen" again for Depth 2/7

---

### 5.3 Publish Workflow
**Guidance:** Test that published lessons retain correct images

**Data:**
- Question: `What is climate change?`
- Expected behavior: Lesson should be publishable with correct image
- Expected image: Climate/environment diagram

**Steps:**
1. Generate lesson: `What is climate change?`
2. Wait for completion
3. In sidebar, click the green upload icon (Publish)
4. Confirm publication
5. Navigate to `/lesson` to view published lessons
6. Click on the published lesson to view it

**Validation:**
- ✓ Lesson publishes successfully
- ✓ Published lesson shows correct answer
- ✓ Published lesson shows correct image (same as in CMS2)
- ✓ Image displays properly on `/lesson` page

---

## Test 6: Logical Validation (Requirements-Driven)

### 6.1 Verify Summarization Improves Image Relevance
**Guidance:** Test that answer summarization leads to better image prompts

**Data:**
- Question: `What is the capital of France and why is it important for European politics and trade?`
- Expected behavior: Image should show Paris/France (not generic "politics")
- Expected image: Paris/France landmark or map

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter: `What is the capital of France and why is it important for European politics and trade?`
3. Click "Generate Lesson"
4. Open DevTools (F12) → Console
5. Look for logs: `[IMG-GEN-STEP2]` (summary) and `[IMG-GEN-STEP3]` (image prompt)
6. Check image in right panel

**Validation:**
- ✓ Console log shows summary contains "Paris" or "France"
- ✓ Console log shows image prompt contains "Paris" or "France"
- ✓ Image shows Paris/France (not generic politics)
- ✓ Summarization successfully extracted core topic

---

### 6.2 Verify Fallback Chain Works
**Guidance:** Test that fallback chain is used correctly

**Data:**
- Multiple questions with different complexity levels
- Expected behavior: Most use Pollinations, some use Wikimedia, rare use placeholder
- Expected image: Valid image from one of the sources

**Steps:**
1. Generate 5 different lessons with different questions:
   - `photosynthesis`
   - `quantum computing`
   - `history of the internet`
   - `machine learning`
   - `renewable energy`
2. For each, open DevTools (F12) → Console
3. Look for logs showing which source was used:
   - `[IMG-GEN-STEP4]` = Pollinations
   - `[IMG-GEN-STEP5-RESULT]` = Wikimedia
   - `[IMG-GEN-STEP6]` = Placeholder

**Validation:**
- ✓ Most lessons use Pollinations (fastest, best quality)
- ✓ Some may use Wikimedia (if Pollinations fails)
- ✓ Rarely use placeholder (only if both fail)
- ✓ All images are valid URLs
- ✓ All images display correctly

---

### 6.3 Verify Rate Limiting Prevents Abuse
**Guidance:** Test that rate limiting is enforced

**Data:**
- Rapid clicks on "Generate Lesson" button
- Expected behavior: 11th request should fail with rate limit error
- Expected error: "Too many requests. Wait X seconds."

**Steps:**
1. Navigate to `/lesson/cms2`
2. Enter a question: `What is artificial intelligence?`
3. Rapidly click "Generate Lesson" button 11 times in succession (within 60 seconds)
4. Check for error message on 11th attempt

**Validation:**
- ✓ First 10 requests succeed
- ✓ 11th request fails with rate limit error
- ✓ Error message shows how many seconds to wait
- ✓ After waiting, can generate again

---

## Summary

All manual tests should pass with the image generation fix in place. The fix ensures:

1. ✓ **Input Validation**: All inputs are safely handled
2. ✓ **URL Validation**: Image URLs are validated before storage
3. ✓ **Fallback Chain**: Multiple fallbacks ensure images always display
4. ✓ **Client-Side Fallback**: Browser-level fallback for broken URLs
5. ✓ **Error Logging**: Comprehensive logs for debugging
6. ✓ **Security**: XSS, SQL injection, and other attacks are mitigated
7. ✓ **Reliability**: Rate limiting and error handling prevent abuse

