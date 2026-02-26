<?php
/**
 * portfolioAPI.php — Handles POST actions for portfolio CMS forms
 * 
 * WHY: Centralizes all portfolio write operations (sections, skills, projects)
 *      behind a single endpoint, matching the blogAPI.php pattern.
 */
require_once __DIR__ . '/../controllers/PortfolioController.php';

$portfolio = new PortfolioController();

// ---------------------------------------------------------------
//  CONTACT form submission (public — no auth required)
//  WHY: Must be checked BEFORE the auth gate so visitors can submit
// ---------------------------------------------------------------
if (isset($_POST['send_contact'])) {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // WHY: Basic server-side validation prevents empty/malicious submissions
    if (empty($name) || empty($email) || empty($message)) {
        $portfolio->alert_redirect('Name, email, and message are required.', '/portfolio#contact');
        exit;
    }

    // WHY: Validate email format to reject obviously bad input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $portfolio->alert_redirect('Invalid email address.', '/portfolio#contact');
        exit;
    }

    $res = $portfolio->insert_contact($name, $email, $subject, $message);
    if ($res) {
        $portfolio->alert_redirect('Thank you! Your message has been sent.', '/portfolio#contact');
    } else {
        $portfolio->alert_redirect('Failed to send message. Please try again.', '/portfolio#contact');
    }
    exit;
}

// WHY: All remaining write operations require authentication
$portfolio->check_auth();

// ---------------------------------------------------------------
//  SECTION upsert
// ---------------------------------------------------------------
if (isset($_POST['upsert_section'])) {
    $section_key = trim($_POST['section_key'] ?? '');
    $title       = trim($_POST['title'] ?? '');
    $content     = trim($_POST['content'] ?? '');
    $sort_order  = (int) ($_POST['sort_order'] ?? 0);

    if (empty($section_key) || empty($title)) {
        $portfolio->alert_redirect('Section key and title are required.', '/portfolio/cms');
        exit;
    }

    $result = $portfolio->upsert_section($section_key, $title, $content, $sort_order);
    if ($result) {
        $portfolio->alert_redirect('Section saved successfully.', '/portfolio/cms');
    } else {
        $portfolio->alert_redirect('Failed to save section.', '/portfolio/cms');
    }
    exit;
}

// ---------------------------------------------------------------
//  SECTION update (from edit form by id)
// ---------------------------------------------------------------
if (isset($_POST['update_section'])) {
    $id         = (int) ($_POST['id'] ?? 0);
    $title      = trim($_POST['title'] ?? '');
    $content    = trim($_POST['content'] ?? '');
    $sort_order = (int) ($_POST['sort_order'] ?? 0);

    if ($id <= 0 || empty($title)) {
        $portfolio->alert_redirect('Invalid section data.', '/portfolio/cms');
        exit;
    }

    $result = $portfolio->update_section($id, $title, $content, $sort_order);
    if ($result) {
        $portfolio->alert_redirect('Section updated.', '/portfolio/cms');
    } else {
        $portfolio->alert_redirect('Failed to update section.', '/portfolio/cms');
    }
    exit;
}

// ---------------------------------------------------------------
//  SKILL insert
// ---------------------------------------------------------------
if (isset($_POST['insert_skill'])) {
    $name       = trim($_POST['name'] ?? '');
    $category   = trim($_POST['category'] ?? '');
    $sort_order = (int) ($_POST['sort_order'] ?? 0);

    if (empty($name)) {
        $portfolio->alert_redirect('Skill name is required.', '/portfolio/cms/skills');
        exit;
    }

    $result = $portfolio->insert_skill($name, $category, $sort_order);
    if ($result) {
        $portfolio->alert_redirect('Skill added.', '/portfolio/cms/skills');
    } else {
        $portfolio->alert_redirect('Failed to add skill.', '/portfolio/cms/skills');
    }
    exit;
}

// ---------------------------------------------------------------
//  SKILL update
// ---------------------------------------------------------------
if (isset($_POST['update_skill'])) {
    $id         = (int) ($_POST['id'] ?? 0);
    $name       = trim($_POST['name'] ?? '');
    $category   = trim($_POST['category'] ?? '');
    $sort_order = (int) ($_POST['sort_order'] ?? 0);

    if ($id <= 0 || empty($name)) {
        $portfolio->alert_redirect('Invalid skill data.', '/portfolio/cms/skills');
        exit;
    }

    $result = $portfolio->update_skill($id, $name, $category, $sort_order);
    if ($result) {
        $portfolio->alert_redirect('Skill updated.', '/portfolio/cms/skills');
    } else {
        $portfolio->alert_redirect('Failed to update skill.', '/portfolio/cms/skills');
    }
    exit;
}

// ---------------------------------------------------------------
//  SKILL delete
// ---------------------------------------------------------------
if (isset($_POST['delete_skill'])) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id <= 0) {
        $portfolio->alert_redirect('Invalid skill ID.', '/portfolio/cms/skills');
        exit;
    }
    $portfolio->delete_skill($id);
    $portfolio->alert_redirect('Skill deleted.', '/portfolio/cms/skills');
    exit;
}

// ---------------------------------------------------------------
//  PROJECT insert
// ---------------------------------------------------------------
if (isset($_POST['insert_project'])) {
    $title        = trim($_POST['title'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $technologies = trim($_POST['technologies'] ?? '');
    $role_text    = trim($_POST['role_text'] ?? '');
    $problem      = trim($_POST['problem'] ?? '');
    $solution     = trim($_POST['solution'] ?? '');
    $result_text  = trim($_POST['result_text'] ?? '');
    $demo_url     = trim($_POST['demo_url'] ?? '');
    $github_url   = trim($_POST['github_url'] ?? '');
    $image_url    = trim($_POST['image_url'] ?? '');
    $sort_order   = (int) ($_POST['sort_order'] ?? 0);
    $is_visible   = isset($_POST['is_visible']) ? 1 : 0;

    if (empty($title)) {
        $portfolio->alert_redirect('Project title is required.', '/portfolio/cms/projects');
        exit;
    }

    $res = $portfolio->insert_project($title, $description, $technologies, $role_text, $problem, $solution, $result_text, $demo_url, $github_url, $image_url, $sort_order, $is_visible);
    if ($res) {
        $portfolio->alert_redirect('Project created.', '/portfolio/cms/projects');
    } else {
        $portfolio->alert_redirect('Failed to create project.', '/portfolio/cms/projects');
    }
    exit;
}

// ---------------------------------------------------------------
//  PROJECT update
// ---------------------------------------------------------------
if (isset($_POST['update_project'])) {
    $id           = (int) ($_POST['id'] ?? 0);
    $title        = trim($_POST['title'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $technologies = trim($_POST['technologies'] ?? '');
    $role_text    = trim($_POST['role_text'] ?? '');
    $problem      = trim($_POST['problem'] ?? '');
    $solution     = trim($_POST['solution'] ?? '');
    $result_text  = trim($_POST['result_text'] ?? '');
    $demo_url     = trim($_POST['demo_url'] ?? '');
    $github_url   = trim($_POST['github_url'] ?? '');
    $image_url    = trim($_POST['image_url'] ?? '');
    $sort_order   = (int) ($_POST['sort_order'] ?? 0);
    $is_visible   = isset($_POST['is_visible']) ? 1 : 0;

    if ($id <= 0 || empty($title)) {
        $portfolio->alert_redirect('Invalid project data.', '/portfolio/cms/projects');
        exit;
    }

    $res = $portfolio->update_project($id, $title, $description, $technologies, $role_text, $problem, $solution, $result_text, $demo_url, $github_url, $image_url, $sort_order, $is_visible);
    if ($res) {
        $portfolio->alert_redirect('Project updated.', '/portfolio/cms/projects');
    } else {
        $portfolio->alert_redirect('Failed to update project.', '/portfolio/cms/projects');
    }
    exit;
}

// ---------------------------------------------------------------
//  PROJECT delete
// ---------------------------------------------------------------
if (isset($_POST['delete_project'])) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id <= 0) {
        $portfolio->alert_redirect('Invalid project ID.', '/portfolio/cms/projects');
        exit;
    }
    $portfolio->delete_project($id);
    $portfolio->alert_redirect('Project deleted.', '/portfolio/cms/projects');
    exit;
}

// ---------------------------------------------------------------
//  CONTACT delete (admin only)
// ---------------------------------------------------------------
if (isset($_POST['delete_contact'])) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id <= 0) {
        $portfolio->alert_redirect('Invalid contact ID.', '/portfolio/cms/contacts');
        exit;
    }
    $portfolio->delete_contact($id);
    $portfolio->alert_redirect('Message deleted.', '/portfolio/cms/contacts');
    exit;
}

// WHY: If no valid action matched, redirect back to CMS to avoid a blank page
$portfolio->alert_redirect('No valid action specified.', '/portfolio/cms');
