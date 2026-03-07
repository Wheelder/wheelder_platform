<?php
/**
 * NoteGenerator - Generate release notes for Vackups
 * Creates formatted changelog entries
 */

class NoteGenerator
{
    private $db;

    public function __construct()
    {
        $this->db = VackupDatabase::getInstance();
    }

    /**
     * Generate release note from template
     */
    public function generateNote($version, $label, $description, $changes = [])
    {
        $date = date('Y-m-d');
        
        $note = "## v{$version} - {$label}\n";
        $note .= "**Released:** {$date}\n\n";
        
        if (!empty($description)) {
            $note .= "{$description}\n\n";
        }

        if (!empty($changes)) {
            $note .= "### Changes\n";
            foreach ($changes as $change) {
                $note .= "- {$change}\n";
            }
            $note .= "\n";
        }

        return $note;
    }

    /**
     * Generate full changelog for a project
     */
    public function generateChangelog($projectId)
    {
        $stmt = $this->db->prepare("
            SELECT v.*, rn.content as release_note
            FROM vackups v
            LEFT JOIN release_notes rn ON rn.vackup_id = v.id
            WHERE v.project_id = :project_id
            ORDER BY v.created_at DESC
        ");
        $stmt->bindValue(':project_id', $projectId, SQLITE3_INTEGER);
        $result = $stmt->execute();

        $changelog = "# Changelog\n\n";
        $changelog .= "All notable changes to this project are documented here.\n\n";

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $date = date('Y-m-d', strtotime($row['created_at']));
            $changelog .= "## v{$row['version']} - {$row['label']}\n";
            $changelog .= "**Released:** {$date}\n\n";
            
            if (!empty($row['description'])) {
                $changelog .= "{$row['description']}\n\n";
            }
            
            if (!empty($row['release_note'])) {
                $changelog .= "{$row['release_note']}\n\n";
            }
            
            $changelog .= "---\n\n";
        }

        return $changelog;
    }

    /**
     * Save release note
     */
    public function saveNote($vackupId, $content)
    {
        // Check if note exists
        $stmt = $this->db->prepare("SELECT id FROM release_notes WHERE vackup_id = :vackup_id");
        $stmt->bindValue(':vackup_id', $vackupId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $existing = $result->fetchArray(SQLITE3_ASSOC);

        if ($existing) {
            // Update
            $stmt = $this->db->prepare("UPDATE release_notes SET content = :content WHERE vackup_id = :vackup_id");
        } else {
            // Insert
            $stmt = $this->db->prepare("INSERT INTO release_notes (vackup_id, content) VALUES (:vackup_id, :content)");
        }

        $stmt->bindValue(':vackup_id', $vackupId, SQLITE3_INTEGER);
        $stmt->bindValue(':content', $content, SQLITE3_TEXT);
        return $stmt->execute();
    }

    /**
     * Get release note for a vackup
     */
    public function getNote($vackupId)
    {
        $stmt = $this->db->prepare("SELECT * FROM release_notes WHERE vackup_id = :vackup_id");
        $stmt->bindValue(':vackup_id', $vackupId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    /**
     * Export changelog to file
     */
    public function exportChangelog($projectId, $outputPath)
    {
        $changelog = $this->generateChangelog($projectId);
        return file_put_contents($outputPath, $changelog) !== false;
    }
}
