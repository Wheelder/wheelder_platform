

CREATE TABLE `affiliate_links` (
  `id` int NOT NULL AUTO_INCREMENT,
  `link` text,
  `referring_code` varchar(50) DEFAULT NULL,
  `referrer_id` int DEFAULT NULL,
  `referral_status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `auto_bids` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `interval_` varchar(20) DEFAULT NULL,
  `current_status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `balance` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `initial_balance` float(10,2) DEFAULT NULL,
  `current_balance` float(10,2) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `owing` float(10,2) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `blogs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `title` text,
  `content` text,
  `file_name` varchar(255) DEFAULT NULL,
  `file_mime` varchar(255) DEFAULT NULL,
  `file_data` longblob,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO blogs VALUES("3","","sdfsdfsdf","xcvsdasdasdas<br>","","","","2023-10-22 13:51:14");
INSERT INTO blogs VALUES("4","","Mastering GraphQL","Introduction to GraphQL

GraphQL is a query language for APIs <br>and a runtime for executing those queries with your existing <br>data. It provides a complete and understandable description of the <br>data in your API, giving clients the power to ask <br>for precisely what they need and nothing more. Some key <br>points about GraphQL include:

- Efficient Data Loading: You write <br>the data you want, and you get exactly the data <br>you want.

- Single Endpoint: GraphQL provides a single endpoint, <br>eliminating the need for multiple versions of the same API.

<br>- Strongly-Typed: GraphQL is strongly-typed, allowing you to validate a <br>query within the GraphQL type system before execution.

Benefits of <br>GraphQL

1. Efficient Data Loading: GraphQL allows you to request <br>the data you need, resulting in more efficient data loading.

<br>2. Single Endpoint: It simplifies API access by providing a <br>single endpoint for all data requests.

3. Strongly-Typed: GraphQL&039;s strong <br>typing system enables better query validation.

Disadvantages of GraphQL

1. <br>High Learning Curve: GraphQL has a steep learning curve, which <br>may be challenging for beginners.

2. HTTP Status Code: GraphQL <br>queries typically return a status code of 200, regardless of <br>query success or failure.

3. Lack of Built-in Caching Support: <br>Unlike REST APIs, GraphQL lacks native HTTP caching support due <br>to its single endpoint.

Advanced GraphQL Concepts

- Fragments: Fragments <br>improve the structure and reusability of GraphQL code by allowing <br>the collection of fields on a specific type.

- Parameterizing <br>Fields with Arguments: GraphQL type definitions enable fields to take <br>arguments, similar to functions in typed programming languages.

- Named <br>Query Results with Aliases: GraphQL allows sending multiple queries in <br>a single request. However, naming conflicts may arise when requesting <br>the same fields.

- Advanced SDL (Schema Definition Language): SDL <br>offers additional language features, including scalar types (String, Int, Float, <br>Boolean, and ID) and more.

Step-by-Step Guide to Create an <br>Advanced GraphQL Server

Follow these steps to set up an <br>advanced GraphQL server using Node.js and Express:

Step 1: Set <br>Up Your Project

Create a new directory and initialize an <br>npm project:

```bash
mkdir graphql-server
cd graphql-server
npm init -y
<br>```

Step 2: Install Dependencies

Install Express and GraphQL dependencies:

<br>```bash
npm install express express-graphql graphql
```

Step 3: Create <br>Your Server File

Create a `server.js` file, which will be <br>the main file of your project:

```bash
touch server.js
```

<br>Step 4: Set Up Your GraphQL Schema

In your `server.js` <br>file, define your GraphQL schema. This is where you specify <br>the types and fields your API can return.

```javascript
// <br>Your schema definition and resolver functions
```

Step 5: Run <br>Your Server

Run your server:

```bash
node server.js
```

Your <br>GraphQL server is now running at `http://localhost:4000/graphql`. You can use <br>GraphiQL, an in-browser tool, to interact with your server and <br>test GraphQL queries.

For more in-depth guidance on advanced GraphQL <br>concepts and detailed examples, refer to online tutorials or the <br>official [GraphQL documentation](https://graphql.org/). Happy coding! ?

Include this... (your existing <br>styles)
Include this... (your existing scripts)<br>","","","","2023-10-22 14:09:09");



CREATE TABLE `blogs_query` (
  `id` int NOT NULL AUTO_INCREMENT,
  `deadline` varchar(255) DEFAULT NULL,
  `user_details` json DEFAULT NULL,
  `content` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `filepath` text,
  `content` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `chatroom` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `token` int DEFAULT NULL,
  `sender` int DEFAULT NULL,
  `destination` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `chats` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sender` int DEFAULT NULL,
  `destination` int DEFAULT NULL,
  `token` int DEFAULT NULL,
  `message` text,
  `status` int DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `cities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `city_name` varchar(255) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `company_profiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `logo` text,
  `website` varchar(255) DEFAULT NULL,
  `number_employee` varchar(255) DEFAULT NULL,
  `description` text,
  `tagline` text,
  `industry` varchar(255) DEFAULT NULL,
  `company_phone` varchar(255) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `vat_id` varchar(255) DEFAULT NULL,
  `gst_id` varchar(255) DEFAULT NULL,
  `pst_id` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `time_zone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `payment_method_id` int DEFAULT NULL,
  `billing_address` text,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `contracts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `availability` json DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `description` longtext,
  `currency` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `final_status` varchar(255) DEFAULT NULL,
  `awarded_to` varchar(255) DEFAULT NULL,
  `week_hours` varchar(255) DEFAULT NULL,
  `fr_status` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `contracts_applications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `applicant_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `contract_id` int DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `availability` varchar(255) DEFAULT NULL,
  `charges` varchar(255) DEFAULT NULL,
  `milestones` varchar(255) DEFAULT NULL,
  `milestone_status` varchar(255) DEFAULT NULL,
  `milestone_amount` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `courses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `picture` text,
  `title` varchar(255) DEFAULT NULL,
  `discipline` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `description` text,
  `rating` varchar(255) DEFAULT NULL,
  `link` text,
  `price` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `file` text,
  `status` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `daily_routine` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `task` text,
  `title` text,
  `description` text,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `deposits` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `deposit_status` text,
  `billing_address` text,
  `currency` varchar(255) DEFAULT NULL,
  `card_no` varchar(255) DEFAULT NULL,
  `card_name` varchar(255) DEFAULT NULL,
  `card_expiry_month` int DEFAULT NULL,
  `card_expiry_year` int DEFAULT NULL,
  `card_cvv` int DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `disputes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contract_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `pro_id` int DEFAULT NULL,
  `ticket_id` int DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `earnings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `project_id` int unsigned DEFAULT NULL,
  `user_balance` varchar(255) DEFAULT NULL,
  `balance_due` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `project_duration` varchar(255) DEFAULT NULL,
  `project_delivery_date` varchar(255) DEFAULT NULL,
  `amount_paid` varchar(255) DEFAULT NULL,
  `amount_due` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `payout_status` varchar(255) DEFAULT NULL,
  `payout_amount` varchar(255) DEFAULT NULL,
  `payout_date` varchar(255) DEFAULT NULL,
  `amount_after_fee` varchar(255) DEFAULT NULL,
  `fee` varchar(255) DEFAULT NULL,
  `payment_deadline` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `external_elite_projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int DEFAULT NULL,
  `freelancer_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `flag` json DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `title` text,
  `bidding_interval` varchar(20) DEFAULT NULL,
  `max_budget` varchar(20) DEFAULT NULL,
  `min_budget` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `details` text,
  `link` text,
  `tag` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `external_projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int DEFAULT NULL,
  `freelancer_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `flag` json DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `title` text,
  `bidding_interval` varchar(20) DEFAULT NULL,
  `max_budget` varchar(20) DEFAULT NULL,
  `min_budget` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `details` text,
  `link` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `external_projects_bidden` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `status` text,
  `status_message` text,
  `error_code` text,
  `request_id` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




CREATE TABLE `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_path` text,
  `key_id` varchar(50) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `financial_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `current_expenditures` decimal(10,2) DEFAULT NULL,
  `operational_cost` varchar(50) DEFAULT NULL,
  `short_term_investments` decimal(10,2) DEFAULT NULL,
  `mid_term_investments` decimal(10,2) DEFAULT NULL,
  `long_term_investments` decimal(10,2) DEFAULT NULL,
  `debts` decimal(10,2) DEFAULT NULL,
  `revenue` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `financial_profile` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `account_type` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `stripe_connect_id` varchar(255) DEFAULT NULL,
  `stripe_customer_id` varchar(255) DEFAULT NULL,
  `stripe_card_id` varchar(255) DEFAULT NULL,
  `card_token` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `bank_token` varchar(255) DEFAULT NULL,
  `stripe_source_id` varchar(255) DEFAULT NULL,
  `stripe_bank_account_id` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `gig_purchase` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gig_id` int DEFAULT NULL,
  `freelancer_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `gigs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `gigtitle` text,
  `category` varchar(255) DEFAULT NULL,
  `subcategory` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `basicpackage` varchar(255) DEFAULT NULL,
  `standardpackage` varchar(255) DEFAULT NULL,
  `premiumpackage` varchar(255) DEFAULT NULL,
  `basicdescribe` varchar(255) DEFAULT NULL,
  `standardescribe` varchar(255) DEFAULT NULL,
  `premiumdescribe` varchar(255) DEFAULT NULL,
  `basic_delieverytime` varchar(255) DEFAULT NULL,
  `standard_delieverytime` varchar(255) DEFAULT NULL,
  `premium_delieverytime` varchar(255) DEFAULT NULL,
  `basic_revisions` varchar(255) DEFAULT NULL,
  `standard_revisions` varchar(255) DEFAULT NULL,
  `premium_revisions` varchar(255) DEFAULT NULL,
  `basicdouble` varchar(255) DEFAULT NULL,
  `standarddouble` varchar(255) DEFAULT NULL,
  `premiumdouble` varchar(255) DEFAULT NULL,
  `basichigh` varchar(255) DEFAULT NULL,
  `standardhigh` varchar(255) DEFAULT NULL,
  `premiumhigh` varchar(255) DEFAULT NULL,
  `basic_price` varchar(255) DEFAULT NULL,
  `standard_price` varchar(255) DEFAULT NULL,
  `premium_price` varchar(255) DEFAULT NULL,
  `step2_description` text,
  `questions` text,
  `answers` text,
  `requirement` text,
  `gigimages` text,
  `gigsvideo` text,
  `gig_status` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `goals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `goal` text,
  `title` text,
  `description` text,
  `deadline` varchar(250) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `job_applications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `client_id` int NOT NULL,
  `job_id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `resume` text,
  `person_specifications` text,
  `skill_requirements` text,
  `qualifications_requirements` text,
  `experience_requirements` text,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `jobs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `subindustry` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `contract_type` varchar(255) DEFAULT NULL,
  `salary` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `person_specifications` text,
  `skill_requirements` text,
  `qualifications_requirements` text,
  `experience_requirements` text,
  `job_description` text,
  `job_file` text,
  `job_status` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `local_services` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `availability` json DEFAULT NULL,
  `service_option_name` json DEFAULT NULL,
  `service_option_price` json DEFAULT NULL,
  `description` text,
  `service_file` text,
  `service_status` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `manage_jobs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `job_id` int DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `job_status` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `membership_plans` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int unsigned DEFAULT NULL,
  `pid` int unsigned DEFAULT NULL,
  `selected_team_id` varchar(255) DEFAULT NULL,
  `applicant_id` int unsigned DEFAULT NULL,
  `blockchain` text,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO migrations VALUES("1","2014_10_12_000000_create_users_table","1");
INSERT INTO migrations VALUES("2","2014_10_12_100000_create_password_reset_tokens_table","1");
INSERT INTO migrations VALUES("3","2019_08_19_000000_create_failed_jobs_table","1");
INSERT INTO migrations VALUES("4","2019_12_14_000001_create_personal_access_tokens_table","1");



CREATE TABLE `milestones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `contract` int unsigned DEFAULT NULL,
  `client` int unsigned DEFAULT NULL,
  `freelancer` int unsigned DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `description` text,
  `ml_payment` decimal(10,2) DEFAULT NULL,
  `ml_payment_inprogress` varchar(250) DEFAULT NULL,
  `ml_payment_released` varchar(250) DEFAULT NULL,
  `release_request` int DEFAULT NULL,
  `release_status` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `due_date` varchar(250) DEFAULT NULL,
  `tasks` int DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text,
  `section_title` varchar(255) DEFAULT NULL,
  `category` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `content` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO notes VALUES("1","Mastering PostgreSQL","","Database","Introduction to PostgreSQL

PostgreSQL is a powerful, open-source relational database management system (RDBMS) known for its advanced features, extensibility, and commitment to standards compliance. It is widely used in applications requiring data storage, retrieval, and management. In this mastering note, we&#039;ll explore key concepts of PostgreSQL and provide a real-time example to illustrate its usage.

Key Concepts in PostgreSQL

1. Relational Database Management: PostgreSQL is an RDBMS, meaning it organizes data into tables with rows and columns, allowing for efficient data retrieval and manipulation.

2. SQL (Structured Query Language): SQL is the language used to interact with PostgreSQL. It enables data retrieval, manipulation, and management through queries and commands.

3. Data Integrity and Constraints: PostgreSQL enforces data integrity using constraints like primary keys, foreign keys, and check constraints to maintain data accuracy and consistency.

4. Extensibility: PostgreSQL supports the creation of user-defined functions, data types, and extensions, allowing developers to tailor the database to specific application needs.

Real-Time Example: Building a Blog Application with PostgreSQL

Let&#039;s demonstrate the application of PostgreSQL by creating a real-time example of a Blog Application using PostgreSQL.

Step 1: PostgreSQL Setup

Install PostgreSQL and set up a database instance for the Blog Application. You can use the official PostgreSQL distribution or a variety of available distributions.

Step 2: Database Schema

Define the database schema with tables like `posts`, `users`, and `comments` to store blog posts, user information, and comments.

Step 3: SQL Queries

Write SQL queries to perform operations on the blog data, such as inserting new posts, updating user profiles, and retrieving blog posts and comments.

```sql
-- Insert a new blog post
INSERT INTO posts (title, content, user_id) VALUES (&#039;My First Post&#039;, &#039;This is the content of my first blog post.&#039;, 1);

-- Update user profile
UPDATE users SET bio = &#039;Blogger and enthusiast&#039; WHERE user_id = 1;

-- Retrieve blog posts and comments
SELECT p.title, p.content, u.username, c.comment_content
FROM posts p
JOIN users u ON p.user_id = u.user_id
LEFT JOIN comments c ON p.post_id = c.post_id;
```

Step 4: Application Integration

Integrate the PostgreSQL database with your Blog Application, which can be web-based or mobile, allowing users to create, read, update, and delete blog content.

The Importance of PostgreSQL

- Open Source: PostgreSQL is open source and free to use, making it cost-effective for businesses and developers.

- Data Integrity: It offers robust data integrity constraints to ensure data accuracy.

- Extensibility: Developers can extend PostgreSQL to meet custom application requirements.

Conclusion

Mastering PostgreSQL is essential for effective data management and storage. The real-time example showcased the development of a Blog Application using PostgreSQL, highlighting the practical application of PostgreSQL in real-world scenarios.","2023-10-22 20:46:12","2023-10-22 21:14:10");



CREATE TABLE `notifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `email_noti` varchar(255) DEFAULT NULL,
  `sms_noti` varchar(255) DEFAULT NULL,
  `noti_posts` varchar(255) DEFAULT NULL,
  `noti_jobs` varchar(255) DEFAULT NULL,
  `noti_blogs` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




CREATE TABLE `payment_methods` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `billing_address` text,
  `currency` varchar(255) DEFAULT NULL,
  `banking_details` json DEFAULT NULL,
  `card_details` json DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) NOT NULL,
  `agent_fee` decimal(10,2) NOT NULL,
  `customer_fee` decimal(10,2) NOT NULL,
  `payment_type` varchar(255) NOT NULL,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




CREATE TABLE `resumes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `address` text,
  `bio` text,
  `skills` text,
  `qualifications` text,
  `experience` text,
  `photo` text,
  `status` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `reviews` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `reviewer_id` int DEFAULT NULL,
  `reviewee_id` int DEFAULT NULL,
  `review` text,
  `date_time` datetime DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `contract_id` int DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `saved_jobs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `job_id` int DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `storage` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `title` text,
  `file_name` varchar(255) DEFAULT NULL,
  `file_mime` varchar(255) DEFAULT NULL,
  `file_data` longblob,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `suggested_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text,
  `content` text,
  `user_details` json DEFAULT NULL,
  `deadline` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `task` text,
  `title` text,
  `description` text,
  `deadline` varchar(250) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `todo_tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `task` text,
  `title` text,
  `description` text,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `todos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `user_subscriptions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `plan_id` int DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `stripe_customer_id` varchar(255) DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `paid_amount` varchar(255) DEFAULT NULL,
  `paid_amount_currency` varchar(255) DEFAULT NULL,
  `plan_interval` varchar(255) DEFAULT NULL,
  `plan_interval_count` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `created` varchar(255) DEFAULT NULL,
  `plan_period_start` varchar(255) DEFAULT NULL,
  `plan_period_end` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `default_app` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `sub_role` varchar(255) DEFAULT NULL,
  `rating` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `current_session` varchar(255) DEFAULT NULL,
  `online` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `gst` varchar(255) DEFAULT NULL,
  `pst` varchar(255) DEFAULT NULL,
  `vat_no` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `email_verified` int DEFAULT NULL,
  `last_login_ip` varchar(255) DEFAULT NULL,
  `last_login_device` varchar(255) DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `referral_code` varchar(255) DEFAULT NULL,
  `user_status` varchar(255) DEFAULT NULL,
  `created_teams` varchar(255) DEFAULT NULL,
  `invited_teams` varchar(255) DEFAULT NULL,
  `joined_teams` varchar(255) DEFAULT NULL,
  `profile_status` varchar(255) DEFAULT NULL,
  `time_zone` varchar(255) DEFAULT NULL,
  `profile_image` text,
  `password` varchar(230) DEFAULT NULL,
  `bio` text,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO users VALUES("2","","","Baays ","Fakhri","","","","1","","","","","","","","","330514","abbf2011@gmail.com","","","","","","","","","","","","","1","","","","","","","","","0","","","123qwe","","2023-10-21 12:33:26");



CREATE TABLE `users_logs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_login_ip` varchar(255) DEFAULT NULL,
  `last_login_device` varchar(255) DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `website_traffic` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text,
  `page_url` text,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `referrer` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


