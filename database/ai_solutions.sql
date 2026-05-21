-- ============================================================
-- AI-Solutions Database Schema & Seed Data
-- Database: ai_solutions
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- DROP TABLES (clean reinstall)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `admin_users`;
DROP TABLE IF EXISTS `contact_inquiries`;
DROP TABLE IF EXISTS `gallery_images`;
DROP TABLE IF EXISTS `articles`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `portfolio_items`;
DROP TABLE IF EXISTS `testimonials`;
DROP TABLE IF EXISTS `events`;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- TABLE: admin_users
-- ------------------------------------------------------------
CREATE TABLE `admin_users` (
    `id`         INT            NOT NULL AUTO_INCREMENT,
    `username`   VARCHAR(50)    NOT NULL,
    `password`   VARCHAR(255)   NOT NULL,
    `created_at` TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLE: contact_inquiries
-- ------------------------------------------------------------
CREATE TABLE `contact_inquiries` (
    `id`           INT           NOT NULL AUTO_INCREMENT,
    `name`         VARCHAR(100)  NOT NULL,
    `email`        VARCHAR(150)  NOT NULL,
    `phone`        VARCHAR(30)   NOT NULL,
    `company_name` VARCHAR(150)  NOT NULL,
    `country`      VARCHAR(100)  NOT NULL,
    `job_title`    VARCHAR(100)  NOT NULL,
    `job_details`  TEXT          NOT NULL,
    `is_read`      TINYINT(1)    NOT NULL DEFAULT 0,
    `submitted_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLE: services
-- ------------------------------------------------------------
CREATE TABLE `services` (
    `id`                INT           NOT NULL AUTO_INCREMENT,
    `title`             VARCHAR(150)  NOT NULL,
    `icon_class`        VARCHAR(100)  NOT NULL DEFAULT 'bi-gear',
    `image_path`        VARCHAR(255)  NOT NULL DEFAULT '',
    `short_description` TEXT          NOT NULL,
    `full_description`  TEXT          NOT NULL,
    `features`          TEXT          NOT NULL,
    `display_order`     INT           NOT NULL DEFAULT 0,
    `created_at`        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLE: portfolio_items
-- ------------------------------------------------------------
CREATE TABLE `portfolio_items` (
    `id`                INT           NOT NULL AUTO_INCREMENT,
    `title`             VARCHAR(200)  NOT NULL,
    `industry`          VARCHAR(100)  NOT NULL,
    `cover_image_path`  VARCHAR(255)  NOT NULL DEFAULT '',
    `short_description` TEXT          NOT NULL,
    `tech_tags`         VARCHAR(255)  NOT NULL DEFAULT '',
    `challenge`         TEXT          NOT NULL,
    `solution`          TEXT          NOT NULL,
    `outcome`           TEXT          NOT NULL,
    `created_at`        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLE: testimonials
-- ------------------------------------------------------------
CREATE TABLE `testimonials` (
    `id`               INT           NOT NULL AUTO_INCREMENT,
    `client_name`      VARCHAR(100)  NOT NULL,
    `job_title`        VARCHAR(100)  NOT NULL,
    `company_name`     VARCHAR(150)  NOT NULL,
    `rating`           TINYINT       NOT NULL DEFAULT 5,
    `testimonial_text` TEXT          NOT NULL,
    `avatar_path`      VARCHAR(255)  NOT NULL DEFAULT '',
    `created_at`       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `chk_rating` CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLE: articles
-- ------------------------------------------------------------
CREATE TABLE `articles` (
    `id`             INT           NOT NULL AUTO_INCREMENT,
    `title`          VARCHAR(200)  NOT NULL,
    `category`       VARCHAR(100)  NOT NULL,
    `excerpt`        TEXT          NOT NULL,
    `content`        LONGTEXT      NOT NULL,
    `thumbnail_path` VARCHAR(255)  NOT NULL DEFAULT '',
    `created_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLE: gallery_images
-- ------------------------------------------------------------
CREATE TABLE `gallery_images` (
    `id`         INT           NOT NULL AUTO_INCREMENT,
    `title`      VARCHAR(150)  NOT NULL,
    `category`   VARCHAR(50)   NOT NULL DEFAULT 'general',
    `image_path` VARCHAR(255)  NOT NULL DEFAULT '',
    `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- TABLE: events
-- ------------------------------------------------------------
CREATE TABLE `events` (
    `id`            INT           NOT NULL AUTO_INCREMENT,
    `title`         VARCHAR(200)  NOT NULL,
    `event_type`    VARCHAR(50)   NOT NULL,
    `event_date`    DATE          NOT NULL,
    `location`      VARCHAR(200)  NOT NULL,
    `description`   TEXT          NOT NULL,
    `register_link` VARCHAR(255)  NOT NULL DEFAULT '',
    `image_path`    VARCHAR(255)  NOT NULL DEFAULT '',
    `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- SEED DATA
-- ============================================================

-- ------------------------------------------------------------
-- SERVICES (6 records — images added via admin panel)
-- ------------------------------------------------------------
INSERT INTO `services` (`title`, `icon_class`, `short_description`, `full_description`, `features`, `display_order`) VALUES
('AI-Powered Virtual Assistant',
 'bi-robot',
 'Intelligent conversational agents that handle employee queries 24/7, reducing support ticket volume and improving response times across your organisation.',
 'Our AI-powered virtual assistants leverage advanced natural language processing to understand and respond to complex employee queries in real time. Deployed across web, mobile, and collaboration platforms, they integrate seamlessly with existing HR, IT, and facilities systems to provide instant, accurate answers without human intervention.',
 'Natural Language Processing (NLP) engine\nMulti-platform deployment (web, mobile, Slack, Teams)\nLive handoff to human agents\nMultilingual support\nAnalytics dashboard for query trends\n24/7 availability with zero downtime',
 1),

('Digital Employee Experience Platform',
 'bi-people-fill',
 'Unified digital workplace portals that centralise tools, resources, and communications — delivering a frictionless experience for every employee from day one.',
 'Our Digital Employee Experience Platform brings together HR self-service, IT support, knowledge management, and internal communications into a single, personalised portal. Employees find what they need instantly, while managers gain visibility into engagement metrics and adoption rates.',
 'Personalised employee dashboard\nSelf-service HR and IT portal\nKnowledge base and document management\nInternal news and communications hub\nMobile-first responsive design\nSSO and Active Directory integration',
 2),

('AI-Based Prototyping Solutions',
 'bi-cpu-fill',
 'Rapid AI prototype development that transforms your concept into a working proof-of-concept in days, not months — reducing risk and accelerating innovation cycles.',
 'We help businesses validate AI ideas quickly and cost-effectively. Our prototyping methodology combines agile sprints with pre-built AI components to deliver functional prototypes that demonstrate real value to stakeholders. We handle data pipeline, model selection, and front-end — so your team can focus on the business problem.',
 'Concept-to-prototype in 2–4 weeks\nPre-built AI component library\nAgile sprint-based delivery\nData pipeline setup and integration\nStakeholder-ready demo environments\nHandover documentation and training',
 3),

('Intelligent Process Automation',
 'bi-gear-wide-connected',
 'RPA and AI-driven workflow automation that eliminates repetitive manual tasks, cuts operational costs, and frees your workforce to focus on higher-value activities.',
 'Our Intelligent Process Automation service combines Robotic Process Automation (RPA) with AI decision-making to automate end-to-end business processes. From invoice processing and data entry to compliance checking and onboarding workflows, we deliver measurable ROI within the first quarter of deployment.',
 'Process discovery and mapping workshops\nRPA bot development and deployment\nAI-driven decision nodes\nException handling and audit logging\nPerformance monitoring and reporting\nScalable cloud or on-premise deployment',
 4),

('Data Analytics & Business Intelligence',
 'bi-bar-chart-fill',
 'Real-time dashboards, predictive analytics, and actionable insights that turn your raw operational data into a competitive advantage for smarter decision-making.',
 'We design and build custom analytics solutions that give leadership and operational teams a live view of KPIs, trends, and anomalies. Our BI implementations connect to existing data warehouses, CRMs, ERPs, and cloud services, delivering insights through intuitive dashboards that require no technical expertise to use.',
 'Custom KPI dashboard design\nReal-time data pipeline integration\nPredictive analytics and forecasting\nSelf-service reporting for business users\nData quality monitoring\nCloud BI (Power BI, Tableau, custom)',
 5),

('Industry-Specific AI Integration',
 'bi-buildings-fill',
 'Tailored AI solutions built for the unique regulatory, workflow, and data challenges of your sector — whether healthcare, finance, manufacturing, retail, or logistics.',
 'Off-the-shelf AI tools rarely account for sector-specific regulations, legacy systems, or domain terminology. Our industry-focused integration service designs AI solutions from the ground up with your sector constraints in mind. We have delivered compliant, production-ready integrations across NHS trusts, financial institutions, manufacturers, and retailers.',
 'Sector-specific compliance (GDPR, FCA, NHS DSP Toolkit)\nLegacy system integration (HL7, SWIFT, SAP)\nDomain-trained AI models\nRegulatory audit trail and reporting\nStaff training and change management\nOngoing support and model retraining',
 6);

-- ------------------------------------------------------------
-- ARTICLES (5 records)
-- ------------------------------------------------------------
INSERT INTO `articles` (`title`, `category`, `excerpt`, `content`, `created_at`) VALUES
('How AI is Revolutionising the Digital Workplace',
 'AI Innovation',
 'Artificial intelligence is no longer a futuristic concept — it is actively reshaping how organisations manage employee experience, internal communications, and operational efficiency right now.',
 '<p>Artificial intelligence is no longer a futuristic concept — it is actively reshaping how organisations manage employee experience, internal communications, and operational efficiency. From intelligent virtual assistants that resolve IT tickets in seconds to predictive analytics that flag employee disengagement before it becomes attrition, AI is becoming the backbone of the modern digital workplace.</p><p>At AI-Solutions, we have spent the past three years working closely with organisations across healthcare, finance, and manufacturing to embed AI tools that genuinely change how work gets done. The results consistently show the same pattern: faster resolution times, lower operational costs, and measurably higher employee satisfaction scores.</p><p>The key lesson we have learned is that AI implementation is not primarily a technology challenge — it is a change management challenge. The organisations that succeed are those that invest as heavily in training, communication, and adoption as they do in the technology itself. When employees understand how AI assists rather than replaces their work, adoption rates soar.</p><p>Looking ahead, the next frontier is hyper-personalisation: AI systems that adapt in real time to each employee''s role, preferences, and working patterns. The digital workplace of 2027 will feel remarkably different from today, and the organisations building the foundations now will have a decisive competitive advantage.</p>',
 '2026-01-15 09:00:00'),

('5 Ways AI-Solutions Has Transformed Healthcare Operations',
 'Industry Spotlight',
 'The healthcare sector faces unique pressures — staff shortages, rising patient demand, and complex compliance requirements. Here is how AI is helping NHS trusts and private providers navigate these challenges.',
 '<p>The healthcare sector faces unique pressures: chronic staff shortages, rising patient demand, complex compliance requirements, and legacy IT infrastructure that was never designed for the digital age. Over the past two years, AI-Solutions has worked with NHS trusts and private healthcare providers across the UK to deploy targeted AI tools that make a measurable difference on the front line.</p><p><strong>1. AI Triage Assistance</strong> — Our NLP-powered triage assistant analyses patient-reported symptoms and directs them to the appropriate care pathway, reducing inappropriate A&E attendances by an average of 23% in pilot sites.</p><p><strong>2. Staff Scheduling Optimisation</strong> — Machine learning models predict shift demand based on historical patterns, seasonal factors, and planned leave, reducing overstaffing costs and closing rota gaps before they appear.</p><p><strong>3. Document Summarisation</strong> — Clinicians spend an estimated 40% of their time on documentation. Our AI summarisation tool reduces discharge letter drafting time from 25 minutes to under 5 minutes per patient.</p><p><strong>4. Compliance Monitoring</strong> — Automated compliance dashboards flag mandatory training gaps, equipment maintenance overdue dates, and policy deviations in real time, significantly reducing CQC audit preparation time.</p><p><strong>5. Employee Helpdesk Automation</strong> — Our virtual assistant handles 68% of staff IT and HR queries without human escalation, freeing helpdesk teams to focus on complex issues.</p>',
 '2026-02-08 10:30:00'),

('The Future of Intelligent Process Automation',
 'Tech Deep Dive',
 'RPA alone is reaching its limits. The next generation of process automation combines machine learning, computer vision, and natural language processing to handle exceptions that rule-based bots cannot.',
 '<p>Robotic Process Automation promised to eliminate repetitive manual work, and for structured, rule-based processes, it delivered. But organisations quickly discovered the limitation: the moment a process involves an exception, an unstructured document, or a judgement call, traditional RPA bots fail and require human intervention.</p><p>Intelligent Process Automation (IPA) addresses this directly by combining RPA with AI capabilities — machine learning for decision-making, computer vision for document understanding, and NLP for unstructured text. The result is automation that can handle the 20% of cases that account for 80% of processing time.</p><p>Consider invoice processing: a traditional RPA bot extracts data from structured invoices perfectly but struggles with handwritten notes, non-standard layouts, or missing purchase order references. An IPA system uses computer vision to extract data from any invoice format and applies ML to match it to the correct purchase order — even with partial information — escalating only the genuinely ambiguous cases to a human reviewer.</p><p>At AI-Solutions, our IPA implementations consistently achieve 85–95% straight-through processing rates, compared to 40–60% for traditional RPA. The remaining exceptions are handled through intelligent escalation workflows that route cases to the right person with full context already populated.</p><p>The organisations investing in IPA today are not just cutting costs — they are building the data infrastructure that will power the next generation of AI-driven insights about their own operations.</p>',
 '2026-03-01 08:00:00'),

('AI-Solutions Expands Operations to European Markets',
 'Company News',
 'Following strong growth in the UK market, AI-Solutions is proud to announce the expansion of its operations to Germany, the Netherlands, and Ireland — bringing our AI-powered workplace solutions to European enterprises.',
 '<p>We are delighted to announce that AI-Solutions is expanding its operations to three new European markets: Germany, the Netherlands, and Ireland. This milestone represents a significant step in our mission to make AI-powered digital employee experiences accessible to organisations of all sizes across Europe.</p><p>The expansion follows a period of strong growth in the UK, where we have delivered over 30 projects across healthcare, finance, manufacturing, and retail sectors. The demand for practical, affordable AI implementation — as opposed to expensive consultancy engagements with uncertain outcomes — is just as acute in continental Europe as it is at home.</p><p>Our European operations will be led by locally-based partner teams who bring deep sector expertise and regulatory knowledge to complement AI-Solutions'' technical capabilities. All solutions will be fully GDPR-compliant and adapted to local employment law and data residency requirements.</p><p>The first European office will open in Amsterdam in Q3 2026, with Frankfurt and Dublin following in Q4. We are currently recruiting for country lead, sales, and technical delivery roles across all three locations — visit our careers page for details.</p><p>This is an exciting chapter for the AI-Solutions team, and we look forward to bringing the same quality of partnership and delivery that our UK clients have come to expect to our new European clients.</p>',
 '2026-04-10 11:00:00'),

('Building a Business Case for AI: A Practical Guide for Leaders',
 'Digital Workplace',
 'Getting board-level approval for AI investment is one of the biggest barriers organisations face. This guide provides a structured framework for building a compelling, evidence-based business case.',
 '<p>One of the most common challenges we hear from IT directors, HR leaders, and operations managers is this: "I know we need AI, but I cannot get the board to approve the investment." Building a compelling business case for AI is genuinely difficult — partly because the benefits are sometimes intangible, partly because the risks are uncertain, and partly because AI projects have a reputation (often unfair) for going over budget and under-delivering.</p><p>This guide offers a practical framework for constructing a business case that speaks the language of finance and risk — the language that boards respond to.</p><p><strong>Step 1: Start with the problem, not the technology.</strong> Never lead with "we want to implement AI." Lead with "we have a problem that costs us £X per year." Quantify the current state: support ticket volume, average handling time, error rates, staff hours on manual tasks. These numbers are your baseline.</p><p><strong>Step 2: Define success metrics upfront.</strong> What does good look like in 12 months? Reduction in support tickets by 30%? Reduction in onboarding time from 10 days to 3? Tie every proposed AI capability to a measurable outcome.</p><p><strong>Step 3: Present a phased approach.</strong> Boards are nervous about large, uncertain investments. A phased approach — prototype in 6 weeks, pilot with one team, then scale — significantly reduces perceived risk and makes it easier to secure initial approval.</p><p><strong>Step 4: Address the people question directly.</strong> The first question any board will ask is "will this replace jobs?" Have a clear, honest answer. AI-Solutions'' experience shows that well-implemented AI typically redeployments staff to higher-value activities rather than eliminating roles — but you must communicate this proactively.</p><p><strong>Step 5: Reference comparable organisations.</strong> Case studies from peer organisations in your sector are powerful. Use our portfolio section to find relevant examples and request detailed case study documents from our team.</p>',
 '2026-04-28 09:30:00');

-- ------------------------------------------------------------
-- PORTFOLIO ITEMS (6 records)
-- ------------------------------------------------------------
INSERT INTO `portfolio_items` (`title`, `industry`, `short_description`, `tech_tags`, `challenge`, `solution`, `outcome`, `created_at`) VALUES
('NHS Digital Triage Assistant',
 'Healthcare',
 'An NLP-powered virtual triage assistant deployed across three NHS trusts to guide patients to the right care pathway and reduce inappropriate A&E attendances.',
 'Python, NLP, React, REST API, NHS Login',
 'Three NHS trusts in the North East were experiencing significant pressure on A&E departments, with an estimated 30% of attendances classified as non-urgent or inappropriate. Staff were stretched, wait times were rising, and patient satisfaction scores were falling. The trusts needed a scalable solution that could operate 24/7 without increasing headcount.',
 'AI-Solutions developed an NLP-powered triage chatbot integrated with the NHS App and the trusts'' patient portals. The assistant collects symptom information through a structured conversation, applies a trained triage model to recommend an appropriate care setting (self-care, pharmacy, GP, urgent care, or A&E), and books appointments directly where applicable. The system is fully compliant with the NHS Digital Service Standard and Data Security and Protection Toolkit.',
 'Across the three trusts, inappropriate A&E attendances fell by 23% within six months of deployment. The assistant handles over 4,000 patient interactions per week with a 91% satisfaction rating. GP appointment wait times reduced by an average of 1.8 days as patients were directed to more appropriate pathways. The project was shortlisted for the HSJ Digital Award 2026.',
 '2025-06-15 10:00:00'),

('FinServe Analytics Intelligence Platform',
 'Finance',
 'A real-time business intelligence platform for a mid-sized UK investment firm, consolidating data from six legacy systems into a single executive dashboard.',
 'Python, Power BI, Azure Data Factory, SQL Server, REST APIs',
 'A 400-person investment management firm was operating with six separate data systems — portfolio management, CRM, compliance, risk, HR, and finance — none of which spoke to each other. Leadership relied on weekly manual spreadsheet consolidations that took two analysts two days each to produce, were frequently inconsistent, and were always at least five days out of date.',
 'AI-Solutions designed and built a centralised data warehouse on Azure, with automated ETL pipelines ingesting data from all six source systems in near real time. A custom Power BI dashboard layer delivers role-specific views for C-suite, compliance, risk, and operations teams. An anomaly detection module flags unusual trading patterns, data quality issues, and compliance breaches automatically, sending alerts to the relevant team within minutes of detection.',
 'The firm now has a live view of its entire operation available to every authorised user on any device. The two-analyst weekly report has been eliminated, saving approximately 400 hours of analyst time per year. Three compliance breaches were caught by the anomaly detection system within the first month of operation — before they would have been identified manually. The CTO described it as "the single biggest operational improvement the firm has made in ten years."',
 '2025-08-20 10:00:00'),

('AutoManufacture Process Optimiser',
 'Manufacturing',
 'An AI-driven production line monitoring and optimisation system for a Midlands automotive components manufacturer, reducing downtime and improving yield.',
 'Python, TensorFlow, MQTT, InfluxDB, Grafana, Edge Computing',
 'A Tier 1 automotive components manufacturer in the West Midlands was experiencing unplanned downtime averaging 18 hours per month across its three production lines, costing an estimated £2.3M per year in lost output and emergency maintenance. Existing monitoring systems only detected failures after they occurred, with no predictive capability.',
 'AI-Solutions deployed an edge computing infrastructure across the three production lines, collecting sensor data at 100ms intervals from 240 monitoring points. Machine learning models were trained on 18 months of historical sensor data to identify the signatures of 12 common failure modes up to 6 hours before occurrence. A Grafana-based operations dashboard gives maintenance engineers real-time visibility of equipment health scores and predicted maintenance windows, allowing proactive intervention.',
 'In the 12 months following deployment, unplanned downtime fell from 18 hours per month to 3.2 hours per month — an 82% reduction. The predictive maintenance capability has enabled the manufacturer to shift from reactive to planned maintenance scheduling, reducing emergency maintenance labour costs by 61%. The estimated annual saving is £1.8M against a project investment of £320,000.',
 '2025-10-05 10:00:00'),

('RetailIQ Customer Intelligence Suite',
 'Retail',
 'A unified customer analytics and personalisation platform for a national UK retailer with 85 stores, integrating loyalty data, purchase history, and real-time browsing behaviour.',
 'Python, Spark, Databricks, Salesforce Marketing Cloud, REST API',
 'A national UK retailer with 85 physical stores and a growing e-commerce operation had accumulated over seven years of customer data across four disconnected systems. Marketing campaigns were broad and generic, customer lifetime value analysis was performed quarterly, and personalisation was limited to basic "you might also like" product recommendations based on category alone. The retailer was losing customers to competitors who offered genuinely personalised experiences.',
 'AI-Solutions unified the retailer''s customer data into a Databricks-powered Customer Data Platform, creating a single customer view updated in real time. ML models were developed for customer lifetime value prediction, churn propensity scoring, next-best-offer recommendation, and optimal contact frequency. These models feed directly into Salesforce Marketing Cloud, enabling fully automated, personalised email and SMS campaigns triggered by individual customer behaviour.',
 'Within nine months, email campaign conversion rates improved by 34%, and average order value for personalised recommendations increased by 28% versus control groups. Churn propensity modelling enabled targeted win-back campaigns that recovered 12% of at-risk customers who would previously have been lost. The retailer attributed a £4.2M increase in annual revenue to the personalisation programme.',
 '2026-01-10 10:00:00'),

('EduBot Learning Assistant',
 'Education',
 'An AI-powered learning assistant for a UK multi-academy trust supporting 8,000 students, providing personalised revision support and flagging at-risk students to teachers.',
 'Python, OpenAI API, React, Node.js, PostgreSQL',
 'A multi-academy trust comprising 12 secondary schools across Yorkshire needed to support 8,000 students with differentiated learning outside classroom hours. Teaching staff were spending significant time answering repetitive homework and revision questions via email, often out of hours. At the same time, student support teams had limited visibility of which students were disengaging before it became a serious attainment issue.',
 'AI-Solutions developed EduBot — a subject-specific AI learning assistant trained on the trust''s own curriculum materials, past papers, and mark schemes. Students interact with EduBot via a web and mobile interface to get explanations, worked examples, and practice questions at their own level. A teacher-facing dashboard aggregates engagement data, quiz performance, and topic confidence scores, automatically flagging students who show early signs of disengagement using a predictive at-risk model.',
 'In the first academic year, 73% of students used EduBot at least weekly. Teacher email traffic for homework support queries fell by 58%. The at-risk flagging system identified 142 students for early intervention who went on to achieve target grades — a cohort that analysis suggests would have been missed under the previous system. The trust has committed to expanding EduBot to primary feeder schools from September 2026.',
 '2026-02-20 10:00:00'),

('LogiFlow Warehouse Automation System',
 'Logistics',
 'An intelligent warehouse management system for a 3PL logistics provider, combining computer vision, route optimisation, and predictive demand forecasting to improve throughput.',
 'Python, Computer Vision, TensorFlow, Flask, React, PostgreSQL',
 'A third-party logistics provider operating a 120,000 sq ft distribution centre in the East Midlands was struggling with increasing order volumes without proportional headcount growth. Manual pick-path routing was inefficient, stock location accuracy was 94.3% (industry best practice is 99.9%+), and demand forecasting was based on simple rolling averages that frequently led to overstocking of slow-moving lines and stockouts of fast-moving products.',
 'AI-Solutions implemented LogiFlow — a three-component system: a computer vision layer using overhead cameras to track stock movements and detect location discrepancies in real time; a route optimisation engine that generates efficient pick paths for warehouse operatives based on order clustering and current warehouse layout; and a demand forecasting module that uses ML to account for seasonality, promotional uplift, and lead time variability in replenishment recommendations.',
 'Stock location accuracy improved to 99.7% within eight weeks of the computer vision system going live. Average pick time per order fell by 31%, enabling the distribution centre to handle a 40% increase in daily order volume without additional headcount. The demand forecasting module reduced overstock write-offs by 44% and eliminated stockouts for the top 200 SKUs. The project delivered full ROI within 14 months.',
 '2026-03-15 10:00:00');

-- ------------------------------------------------------------
-- TESTIMONIALS (8 records)
-- ------------------------------------------------------------
INSERT INTO `testimonials` (`client_name`, `job_title`, `company_name`, `rating`, `testimonial_text`, `created_at`) VALUES
('Dr. Sarah Mitchell',
 'Chief Digital Officer',
 'Northern Care NHS Trust',
 5,
 'AI-Solutions delivered exactly what they promised, on time and within budget — which alone puts them in a rare category. But beyond the technical delivery, what impressed us most was their understanding of the NHS context. They never once tried to sell us technology for its own sake. They genuinely partnered with our clinical and operational teams to solve a real problem, and the results have been transformational.',
 '2025-07-10 10:00:00'),

('James Okafor',
 'Head of Technology',
 'Meridian Investment Management',
 5,
 'We had been talking about consolidating our data systems for years without making progress. AI-Solutions came in, understood the complexity of our legacy environment without flinching, and delivered a working platform in four months. The anomaly detection capability alone has already more than justified the investment. I would recommend them without hesitation to any financial services firm.',
 '2025-10-15 10:00:00'),

('Claire Hutchinson',
 'Operations Director',
 'AutoPart Solutions Ltd',
 5,
 'The predictive maintenance system has genuinely changed how we run our production lines. We have gone from firefighting breakdowns to planning maintenance in advance, which has had a huge knock-on effect on morale as well as costs. The team from AI-Solutions were excellent throughout — technically brilliant and genuinely easy to work with.',
 '2026-01-20 10:00:00'),

('Rajesh Patel',
 'Chief Marketing Officer',
 'Hartley & Browne Retail',
 4,
 'The personalisation platform has exceeded our expectations in terms of conversion uplift. My only note would be that the initial data migration phase took slightly longer than projected, which pushed our launch back by three weeks. That said, the team were transparent about the delay and the end result has been excellent. The ROI is very clear.',
 '2026-02-14 10:00:00'),

('Professor Anne Walters',
 'Director of Learning & Teaching',
 'Yorkshire Multi-Academy Trust',
 5,
 'EduBot has been a genuine game-changer for our students, particularly those who were reluctant to ask questions in class. The fact that it is built on our own curriculum materials makes it feel relevant and trustworthy to both students and teachers. The at-risk flagging has already made a difference to outcomes for students who might otherwise have slipped through the net.',
 '2026-03-05 10:00:00'),

('Marcus Webb',
 'Warehouse General Manager',
 'Swift3PL Distribution',
 5,
 'I was sceptical at the start — we had tried two technology projects before that had not delivered. What was different with AI-Solutions was that they spent the first three weeks doing proper discovery, understanding our actual workflows rather than imposing a pre-built solution. The results speak for themselves: we are handling 40% more orders with the same team.',
 '2026-03-28 10:00:00'),

('Fiona Drummond',
 'HR Director',
 'Caledonian Financial Group',
 4,
 'We brought AI-Solutions in to deploy a virtual HR assistant and the project went smoothly. The assistant handles around 65% of employee queries without escalation, which has freed my team to focus on complex employee relations matters. I would have given five stars but the initial training data preparation was more effort than we had anticipated — something to factor in for future buyers.',
 '2026-04-12 10:00:00'),

('Daniel Reeves',
 'IT Director',
 'NorthBridge Manufacturing',
 5,
 'From the initial discovery workshop to go-live, AI-Solutions were professional, communicative, and technically excellent. They pushed back constructively when we had unrealistic expectations, which I actually appreciated — it showed they cared about the outcome, not just closing the contract. The process automation solution we built together is now core to our operations.',
 '2026-04-30 10:00:00');

-- ------------------------------------------------------------
-- GALLERY IMAGES (6 records — image_path empty; upload via admin)
-- ------------------------------------------------------------
INSERT INTO `gallery_images` (`title`, `category`) VALUES
('AI Innovation Summit 2025 — Opening Keynote',   'promotional'),
('Team Building Day — Sunderland HQ',             'team'),
('Partnership Signing with MediTech UK',          'partner'),
('AI-Solutions Product Launch — January 2025',    'product'),
('Northern Tech Expo — Exhibition Stand',         'promotional'),
('Graduate Recruitment Day 2025',                 'team');

-- ------------------------------------------------------------
-- EVENTS (past + upcoming relative to May 2026)
-- ------------------------------------------------------------
INSERT INTO `events` (`title`, `event_type`, `event_date`, `location`, `description`, `register_link`) VALUES
('AI-Solutions Company Launch',
 'Networking',
 '2025-01-10',
 'Sunderland, UK',
 'The official launch of AI-Solutions, bringing together industry leaders, investors, and technology partners to celebrate the founding of the company and its vision for the future of the digital employee experience.',
 ''),

('FinTech AI Workshop',
 'Workshop',
 '2025-03-05',
 'London, UK',
 'A hands-on workshop for financial services professionals exploring practical applications of AI in compliance monitoring, fraud detection, and customer experience automation. Delivered in partnership with the Fintech Alliance.',
 ''),

('Healthcare Innovation Conference',
 'Conference',
 '2025-11-20',
 'Leeds, UK',
 'AI-Solutions presented research findings from the NHS Digital Triage project at this national healthcare innovation conference, drawing an audience of over 400 NHS and private healthcare professionals.',
 ''),

('Digital Transformation Summit 2026',
 'Conference',
 '2026-03-22',
 'Newcastle, UK',
 'AI-Solutions sponsored and delivered a keynote session at this regional digital transformation summit, sharing lessons learned from over 30 AI implementation projects across diverse sectors.',
 ''),

('AI-Solutions First Anniversary Gala',
 'Networking',
 '2026-01-15',
 'Sunderland, UK',
 'Celebrating one year of AI-Solutions with clients, partners, and the team. The evening featured client testimonials, a preview of upcoming product developments, and an awards ceremony recognising outstanding project outcomes.',
 ''),

('AI Innovation Summit 2026',
 'Conference',
 '2026-07-15',
 'Birmingham, UK',
 'Join us at the UK''s premier AI innovation conference, where AI-Solutions will be presenting our latest research on AI adoption in SMEs and hosting a live demonstration of our newest platform capabilities. Over 800 delegates expected.',
 '#'),

('Digital Workplace Webinar: AI for HR Leaders',
 'Webinar',
 '2026-08-03',
 'Online',
 'A free 90-minute webinar designed for HR directors and people leaders exploring how AI virtual assistants, predictive analytics, and intelligent automation can transform the employee experience. Live Q&A session included.',
 '#'),

('Northern Tech Expo 2026',
 'Exhibition',
 '2026-09-20',
 'Manchester, UK',
 'Visit the AI-Solutions stand at Northern Tech Expo 2026 to see live demonstrations of our full platform suite. The team will be available throughout the two-day event for informal consultations and discovery conversations.',
 '#');
