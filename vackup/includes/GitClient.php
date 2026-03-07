<?php
/**
 * GitClient - Execute local git commands for Vackup
 * WHY: Separate from GitHubClient because this runs local shell commands,
 * while GitHubClient handles REST API calls. Different concerns, different risks.
 */

class GitClient
{
    private $projectPath;

    public function __construct($projectPath)
    {
        $this->projectPath = $projectPath;
    }

    /**
     * Check if the project directory is a git repository
     * WHY: Must verify before attempting any git operations to give
     * a clear error message instead of cryptic shell failures
     */
    public function isGitRepo()
    {
        $result = $this->exec('rev-parse --is-inside-work-tree');
        return $result['success'] && trim($result['output']) === 'true';
    }

    /**
     * Get current branch name
     * WHY: Need to know which branch to push to; user might not be on 'main'
     */
    public function getCurrentBranch()
    {
        $result = $this->exec('rev-parse --abbrev-ref HEAD');
        if ($result['success']) {
            return trim($result['output']);
        }
        return null;
    }

    /**
     * Check if there are uncommitted changes (staged or unstaged)
     * WHY: git commit will fail if there's nothing to commit;
     * detecting this early provides a better error message
     */
    public function hasChanges()
    {
        $result = $this->exec('status --porcelain');
        return $result['success'] && !empty(trim($result['output']));
    }

    /**
     * Stage all changes, commit, and push
     * WHY: This is the core "auto-commit" operation for Vackup.
     * Returns commit SHA on success so we can store it in the database.
     */
    public function commitAndPush($message, $branch = null)
    {
        // WHY: Detect branch if not provided, so we push to the right place
        if (!$branch) {
            $branch = $this->getCurrentBranch();
            if (!$branch) {
                return ['success' => false, 'error' => 'Could not determine current branch'];
            }
        }

        // WHY: Check if remote origin exists before doing work that can't be pushed
        if (!$this->hasRemote('origin')) {
            return ['success' => false, 'error' => 'No remote "origin" configured. Add a remote first.'];
        }

        // Stage all changes
        $addResult = $this->exec('add -A');
        if (!$addResult['success']) {
            return ['success' => false, 'error' => 'git add failed: ' . $addResult['error']];
        }

        // WHY: Check if there's actually anything to commit after staging.
        // A project with no changes should not create an empty commit.
        if (!$this->hasStagedChanges()) {
            return [
                'success' => true,
                'sha' => $this->getLastCommitSha(),
                'branch' => $branch,
                'skipped' => true,
                'message' => 'No changes to commit'
            ];
        }

        // Commit
        // WHY: escapeshellarg() prevents command injection via the commit message
        $commitResult = $this->exec('commit -m ' . escapeshellarg($message));
        if (!$commitResult['success']) {
            return ['success' => false, 'error' => 'git commit failed: ' . $commitResult['error']];
        }

        // Get the commit SHA
        $sha = $this->getLastCommitSha();

        // Push
        $pushResult = $this->exec('push origin ' . escapeshellarg($branch));
        if (!$pushResult['success']) {
            // WHY: Commit succeeded but push failed — report both states
            // so user knows the commit exists locally
            return [
                'success' => false,
                'error' => 'git push failed: ' . $pushResult['error'],
                'sha' => $sha,
                'committed' => true
            ];
        }

        return [
            'success' => true,
            'sha' => $sha,
            'branch' => $branch,
            'skipped' => false
        ];
    }

    /**
     * Check if there are staged changes ready to commit
     * WHY: git diff --cached --quiet exits 1 when there ARE differences, 0 when clean
     */
    private function hasStagedChanges()
    {
        $result = $this->exec('diff --cached --quiet');
        return !$result['success'];
    }

    /**
     * Get SHA of the last commit
     */
    public function getLastCommitSha()
    {
        $result = $this->exec('rev-parse HEAD');
        return $result['success'] ? trim($result['output']) : null;
    }

    /**
     * Check if a remote exists
     * WHY: Push will fail if no remote is configured; detect early
     */
    public function hasRemote($name = 'origin')
    {
        $result = $this->exec('remote get-url ' . escapeshellarg($name));
        return $result['success'];
    }

    /**
     * Execute a git command in the project directory
     * WHY: Uses proc_open instead of shell_exec/exec for reliable stderr capture
     * on both Windows and Linux. The cwd parameter handles cross-platform
     * path differences without needing 'cd' in the command string.
     */
    private function exec($command)
    {
        $fullCommand = 'git ' . $command;

        $descriptors = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w'],  // stderr
        ];

        // WHY: cwd parameter ensures git runs in the project directory,
        // not in the web server's working directory
        $process = proc_open($fullCommand, $descriptors, $pipes, $this->projectPath);

        if (!is_resource($process)) {
            return [
                'success' => false,
                'output' => '',
                'error' => 'Failed to execute git command',
                'exit_code' => -1
            ];
        }

        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        return [
            'success' => $exitCode === 0,
            'output' => $stdout,
            'error' => $stderr,
            'exit_code' => $exitCode
        ];
    }
}
