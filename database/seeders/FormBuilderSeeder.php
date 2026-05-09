<?php

namespace Database\Seeders;

use App\Models\FieldType;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormSubmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormBuilderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ─── 1. Field Types ───────────────────────────────────────────
        $fieldTypes = [
            ['name' => 'Text Input', 'type' => 'text', 'icon' => 'text', 'default_settings' => ['placeholder' => 'Enter text...'], 'default_validation' => ['required' => false, 'min_length' => null, 'max_length' => null, 'regex' => null], 'default_styles' => ['width' => 'full']],
            ['name' => 'Email', 'type' => 'email', 'icon' => 'email', 'default_settings' => ['placeholder' => 'Enter email address...'], 'default_validation' => ['required' => false, 'regex' => null], 'default_styles' => ['width' => 'full']],
            ['name' => 'Number', 'type' => 'number', 'icon' => 'number', 'default_settings' => ['placeholder' => '0', 'min' => null, 'max' => null, 'step' => 1], 'default_validation' => ['required' => false], 'default_styles' => ['width' => 'full']],
            ['name' => 'Phone', 'type' => 'tel', 'icon' => 'phone', 'default_settings' => ['placeholder' => '+1 (000) 000-0000'], 'default_validation' => ['required' => false, 'regex' => '/^\+?[\d\s\-\(\)]+$/'], 'default_styles' => ['width' => 'full']],
            ['name' => 'Long Text', 'type' => 'textarea', 'icon' => 'textarea', 'default_settings' => ['placeholder' => 'Enter your message...', 'rows' => 4], 'default_validation' => ['required' => false, 'min_length' => null, 'max_length' => null], 'default_styles' => ['width' => 'full']],
            ['name' => 'Dropdown', 'type' => 'select', 'icon' => 'select', 'default_settings' => ['placeholder' => 'Select an option', 'options' => ['Option 1', 'Option 2', 'Option 3']], 'default_validation' => ['required' => false], 'default_styles' => ['width' => 'full']],
            ['name' => 'Checkbox', 'type' => 'checkbox', 'icon' => 'checkbox', 'default_settings' => ['options' => ['Option 1', 'Option 2', 'Option 3']], 'default_validation' => ['required' => false], 'default_styles' => ['width' => 'full']],
            ['name' => 'Radio Group', 'type' => 'radio', 'icon' => 'radio', 'default_settings' => ['options' => ['Option A', 'Option B', 'Option C']], 'default_validation' => ['required' => false], 'default_styles' => ['width' => 'full']],
            ['name' => 'Date Picker', 'type' => 'date', 'icon' => 'date', 'default_settings' => [], 'default_validation' => ['required' => false], 'default_styles' => ['width' => 'full']],
            ['name' => 'File Upload', 'type' => 'file', 'icon' => 'file', 'default_settings' => ['accept' => '*', 'multiple' => false], 'default_validation' => ['required' => false], 'default_styles' => ['width' => 'full']],
            ['name' => 'URL', 'type' => 'url', 'icon' => 'url', 'default_settings' => ['placeholder' => 'https://'], 'default_validation' => ['required' => false, 'regex' => '/^https?:\/\/.+/'], 'default_styles' => ['width' => 'full']],
            ['name' => 'Password', 'type' => 'password', 'icon' => 'password', 'default_settings' => ['placeholder' => 'Enter password...'], 'default_validation' => ['required' => false, 'min_length' => 8, 'regex' => null], 'default_styles' => ['width' => 'full']],
        ];

        foreach ($fieldTypes as $ft) {
            FieldType::updateOrCreate(['type' => $ft['type']], $ft);
        }

        $types = FieldType::all()->keyBy('type');

        // ─── 2. FORM 1: Contact Us ────────────────────────────────────
        $contact = Form::create([
            'title' => 'Contact Us',
            'slug' => 'contact-us',
            'description' => 'Get in touch with our team.',
            'status' => 'published',
            'framework' => 'tailwind',
            'settings' => ['success_message' => 'Thank you for reaching out! We will get back to you within 24 hours.'],
        ]);

        $contactFields = [
            ['type' => 'text', 'label' => 'Full Name', 'name' => 'full_name', 'settings' => ['placeholder' => 'John Doe'], 'validation' => ['required' => true, 'min_length' => 2, 'max_length' => 100], 'styles' => ['width' => 'full']],
            ['type' => 'email', 'label' => 'Email Address', 'name' => 'email', 'settings' => ['placeholder' => 'john@example.com'], 'validation' => ['required' => true], 'styles' => ['width' => 'full', 'background' => 'bg-blue-50', 'text_color' => 'text-blue-700']],
            ['type' => 'tel', 'label' => 'Phone Number', 'name' => 'phone', 'settings' => ['placeholder' => '+1 (555) 000-0000'], 'validation' => ['required' => false, 'regex' => '/^\+?[\d\s\-\(\)]+$/'], 'styles' => ['width' => 'half']],
            ['type' => 'select', 'label' => 'Subject', 'name' => 'subject', 'settings' => ['placeholder' => 'Choose a subject', 'options' => ['General Inquiry', 'Sales', 'Support', 'Billing', 'Partnership']], 'validation' => ['required' => true], 'styles' => ['width' => 'half', 'background' => 'bg-green-50']],
            ['type' => 'textarea', 'label' => 'Message', 'name' => 'message', 'settings' => ['placeholder' => 'Tell us how we can help...', 'rows' => 5], 'validation' => ['required' => true, 'min_length' => 10, 'max_length' => 1000], 'styles' => ['width' => 'full', 'text_color' => 'text-red-700']],
        ];

        foreach ($contactFields as $i => $f) {
            FormField::create([
                'form_id' => $contact->id,
                'field_type_id' => $types[$f['type']]->id,
                'label' => $f['label'],
                'name' => $f['name'],
                'order' => $i,
                'settings' => $f['settings'],
                'validation' => $f['validation'],
                'styles' => $f['styles'],
            ]);
        }

        // Submissions for Contact Us
        $contactSubmissions = [
            ['full_name' => 'Alice Johnson', 'email' => 'alice@example.com', 'phone' => '+1 555-1001', 'subject' => 'General Inquiry', 'message' => 'Hi, I wanted to know more about your services. Can you send me a brochure?'],
            ['full_name' => 'Bob Martinez', 'email' => 'bob@techcorp.com', 'phone' => '+1 555-1002', 'subject' => 'Sales', 'message' => 'We are looking for an enterprise plan. Please have someone from sales contact me.'],
            ['full_name' => 'Carol White', 'email' => 'carol@gmail.com', 'phone' => '', 'subject' => 'Support', 'message' => 'My account is locked and I cannot reset my password. Please help!'],
            ['full_name' => 'David Lee', 'email' => 'david@startup.io', 'phone' => '+1 555-1004', 'subject' => 'Partnership', 'message' => 'We are interested in a partnership opportunity. Let\'s set up a call.'],
            ['full_name' => 'Eva Brown', 'email' => 'eva@company.com', 'phone' => '+1 555-1005', 'subject' => 'Billing', 'message' => 'I was charged twice for my subscription this month. Please investigate.'],
            ['full_name' => 'Frank Wilson', 'email' => 'frank@design.co', 'phone' => '+1 555-1006', 'subject' => 'General Inquiry', 'message' => 'Do you offer custom integrations with third-party tools?'],
            ['full_name' => 'Grace Kim', 'email' => 'grace@agency.net', 'phone' => '+1 555-1007', 'subject' => 'Sales', 'message' => 'I represent a digital agency and we have multiple clients who could benefit from your platform.'],
            ['full_name' => 'Henry Clark', 'email' => 'henry@free.me', 'phone' => '', 'subject' => 'Support', 'message' => 'The export feature is broken. I get a 500 error every time.'],
        ];

        foreach ($contactSubmissions as $sub) {
            FormSubmission::create([
                'form_id' => $contact->id,
                'data' => $sub,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ]);
        }

        // ─── 3. FORM 2: Job Application ───────────────────────────────
        $job = Form::create([
            'title' => 'Job Application',
            'slug' => 'job-application',
            'description' => 'Apply for an open position at our company.',
            'status' => 'published',
            'framework' => 'tailwind',
            'settings' => ['success_message' => 'Thank you for applying! Our HR team will review your application and contact you within 5 business days.'],
        ]);

        $jobFields = [
            ['type' => 'text', 'label' => 'Full Name', 'name' => 'full_name', 'settings' => ['placeholder' => 'Your full legal name'], 'validation' => ['required' => true, 'min_length' => 2, 'max_length' => 100], 'styles' => ['width' => 'full']],
            ['type' => 'email', 'label' => 'Email Address', 'name' => 'email', 'settings' => ['placeholder' => 'you@example.com'], 'validation' => ['required' => true], 'styles' => ['width' => 'half', 'background' => 'bg-yellow-50']],
            ['type' => 'tel', 'label' => 'Phone Number', 'name' => 'phone', 'settings' => ['placeholder' => '+1 (555) 000-0000'], 'validation' => ['required' => true, 'regex' => '/^\+?[\d\s\-\(\)]+$/'], 'styles' => ['width' => 'half', 'text_color' => 'text-purple-700']],
            ['type' => 'select', 'label' => 'Position Applying For', 'name' => 'position', 'settings' => ['placeholder' => 'Select a role', 'options' => ['Software Engineer', 'Product Designer', 'Marketing Manager', 'Data Analyst', 'DevOps Engineer', 'Customer Success']], 'validation' => ['required' => true], 'styles' => ['width' => 'full', 'background' => 'bg-gray-50']],
            ['type' => 'select', 'label' => 'Years of Experience', 'name' => 'experience', 'settings' => ['placeholder' => 'Select range', 'options' => ['0–1 years', '1–3 years', '3–5 years', '5–10 years', '10+ years']], 'validation' => ['required' => true], 'styles' => ['width' => 'half']],
            ['type' => 'url', 'label' => 'LinkedIn Profile', 'name' => 'linkedin', 'settings' => ['placeholder' => 'https://linkedin.com/in/...'], 'validation' => ['required' => false, 'regex' => '/^https?:\/\/.+/'], 'styles' => ['width' => 'half']],
            ['type' => 'url', 'label' => 'Portfolio / GitHub', 'name' => 'portfolio', 'settings' => ['placeholder' => 'https://'], 'validation' => ['required' => false, 'regex' => '/^https?:\/\/.+/'], 'styles' => ['width' => 'full']],
            ['type' => 'radio', 'label' => 'Employment Type', 'name' => 'employment_type', 'settings' => ['options' => ['Full-time', 'Part-time', 'Contract', 'Internship']], 'validation' => ['required' => true], 'styles' => ['width' => 'full']],
            ['type' => 'checkbox', 'label' => 'Available to Work', 'name' => 'availability', 'settings' => ['options' => ['Remote', 'On-site', 'Hybrid', 'Willing to Relocate']], 'validation' => ['required' => false], 'styles' => ['width' => 'full']],
            ['type' => 'textarea', 'label' => 'Cover Letter', 'name' => 'cover_letter', 'settings' => ['placeholder' => 'Tell us why you are a great fit for this role...', 'rows' => 6], 'validation' => ['required' => true, 'min_length' => 50, 'max_length' => 2000], 'styles' => ['width' => 'full']],
            ['type' => 'date', 'label' => 'Earliest Start Date', 'name' => 'start_date', 'settings' => [], 'validation' => ['required' => false], 'styles' => ['width' => 'half']],
            ['type' => 'number', 'label' => 'Expected Salary (USD)', 'name' => 'expected_salary', 'settings' => ['placeholder' => '60000', 'min' => 0], 'validation' => ['required' => false], 'styles' => ['width' => 'half']],
        ];

        foreach ($jobFields as $i => $f) {
            FormField::create([
                'form_id' => $job->id,
                'field_type_id' => $types[$f['type']]->id,
                'label' => $f['label'],
                'name' => $f['name'],
                'order' => $i,
                'settings' => $f['settings'],
                'validation' => $f['validation'],
                'styles' => $f['styles'],
            ]);
        }

        // Submissions for Job Application
        $jobSubmissions = [
            ['full_name' => 'Sophia Turner', 'email' => 'sophia@dev.io', 'phone' => '+1 555-2001', 'position' => 'Software Engineer', 'experience' => '3–5 years', 'linkedin' => 'https://linkedin.com/in/sophia-turner', 'portfolio' => 'https://github.com/sophiat', 'employment_type' => 'Full-time', 'availability' => ['Remote', 'Hybrid'], 'cover_letter' => 'I am a passionate full-stack developer with experience in Laravel and Vue.js. I have built several SaaS products and am excited about this opportunity.', 'start_date' => '2024-03-01', 'expected_salary' => 95000],
            ['full_name' => 'James Patel', 'email' => 'james@ux.design', 'phone' => '+1 555-2002', 'position' => 'Product Designer', 'experience' => '5–10 years', 'linkedin' => 'https://linkedin.com/in/james-patel', 'portfolio' => 'https://behance.net/jamespatel', 'employment_type' => 'Full-time', 'availability' => ['On-site', 'Hybrid'], 'cover_letter' => 'With over 7 years designing B2B SaaS products, I bring a user-centric approach paired with strong business thinking. My work has driven 40% conversion improvements.', 'start_date' => '2024-02-15', 'expected_salary' => 110000],
            ['full_name' => 'Mia Chen', 'email' => 'mia.chen@data.co', 'phone' => '+1 555-2003', 'position' => 'Data Analyst', 'experience' => '1–3 years', 'linkedin' => 'https://linkedin.com/in/mia-chen', 'portfolio' => '', 'employment_type' => 'Full-time', 'availability' => ['Remote'], 'cover_letter' => 'I am a data analyst skilled in Python, SQL, and Tableau. I love turning messy datasets into clear, actionable insights for product and marketing teams.', 'start_date' => '2024-03-15', 'expected_salary' => 72000],
            ['full_name' => 'Noah Adams', 'email' => 'noah@cloud.ops', 'phone' => '+1 555-2004', 'position' => 'DevOps Engineer', 'experience' => '3–5 years', 'linkedin' => 'https://linkedin.com/in/noah-adams', 'portfolio' => 'https://github.com/noahadams', 'employment_type' => 'Contract', 'availability' => ['Remote', 'Willing to Relocate'], 'cover_letter' => 'I specialize in Kubernetes, Terraform, and CI/CD pipelines. I have reduced deployment times by 60% at my current company and want to bring that expertise to your team.', 'start_date' => '2024-02-01', 'expected_salary' => 130000],
            ['full_name' => 'Olivia Scott', 'email' => 'olivia@market.pro', 'phone' => '+1 555-2005', 'position' => 'Marketing Manager', 'experience' => '5–10 years', 'linkedin' => 'https://linkedin.com/in/olivia-scott', 'portfolio' => 'https://oliviascott.co', 'employment_type' => 'Full-time', 'availability' => ['On-site'], 'cover_letter' => 'I have led growth marketing at two funded startups, growing MRR by 3x within 12 months using content, SEO, and paid channels. I am ready for my next challenge.', 'start_date' => '2024-04-01', 'expected_salary' => 105000],
            ['full_name' => 'Liam Harris', 'email' => 'liam@fresh.grad', 'phone' => '+1 555-2006', 'position' => 'Customer Success', 'experience' => '0–1 years', 'linkedin' => '', 'portfolio' => '', 'employment_type' => 'Full-time', 'availability' => ['Remote', 'Hybrid'], 'cover_letter' => 'I am a recent graduate eager to start my career in customer success. I am a fast learner, empathetic communicator, and am passionate about helping customers succeed.', 'start_date' => '2024-03-01', 'expected_salary' => 50000],
            ['full_name' => 'Ava Rodriguez', 'email' => 'ava@senior.dev', 'phone' => '+1 555-2007', 'position' => 'Software Engineer', 'experience' => '10+ years', 'linkedin' => 'https://linkedin.com/in/ava-rodriguez', 'portfolio' => 'https://github.com/avaro', 'employment_type' => 'Part-time', 'availability' => ['Remote'], 'cover_letter' => 'After 12 years in software engineering I am looking to reduce hours and take a part-time role. I bring deep expertise in distributed systems, Go, and PostgreSQL.', 'start_date' => '2024-05-01', 'expected_salary' => 80000],
            ['full_name' => 'Ethan Brooks', 'email' => 'ethan@analyst.me', 'phone' => '+1 555-2008', 'position' => 'Data Analyst', 'experience' => '3–5 years', 'linkedin' => 'https://linkedin.com/in/ethan-brooks', 'portfolio' => '', 'employment_type' => 'Full-time', 'availability' => ['Hybrid', 'On-site'], 'cover_letter' => 'I have 4 years of analytics experience at a fintech company, building dashboards and running A/B tests. Proficient in dbt, BigQuery, and Looker.', 'start_date' => '2024-03-20', 'expected_salary' => 88000],
        ];

        foreach ($jobSubmissions as $sub) {
            FormSubmission::create([
                'form_id' => $job->id,
                'data' => $sub,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ]);
        }

        // ─── 4. FORM 3: Event Registration ───────────────────────────
        $event = Form::create([
            'title' => 'Tech Conference 2024 — Registration',
            'slug' => 'tech-conference-2024',
            'description' => 'Register for our annual technology conference on April 20–21, 2024 in San Francisco.',
            'status' => 'published',
            'framework' => 'tailwind',
            'settings' => ['success_message' => 'You are registered! Check your email for a confirmation and calendar invite.'],
        ]);

        $eventFields = [
            ['type' => 'text', 'label' => 'First Name', 'name' => 'first_name', 'settings' => ['placeholder' => 'John'], 'validation' => ['required' => true]],
            ['type' => 'text', 'label' => 'Last Name', 'name' => 'last_name', 'settings' => ['placeholder' => 'Doe'], 'validation' => ['required' => true]],
            ['type' => 'email', 'label' => 'Work Email', 'name' => 'email', 'settings' => ['placeholder' => 'you@company.com'], 'validation' => ['required' => true]],
            ['type' => 'text', 'label' => 'Company / Org', 'name' => 'company', 'settings' => ['placeholder' => 'Acme Inc.'], 'validation' => ['required' => true]],
            ['type' => 'text', 'label' => 'Job Title', 'name' => 'job_title', 'settings' => ['placeholder' => 'e.g. Software Engineer'], 'validation' => ['required' => false]],
            ['type' => 'select', 'label' => 'Ticket Type', 'name' => 'ticket_type', 'settings' => ['placeholder' => 'Select ticket', 'options' => ['General Admission ($199)', 'VIP ($499)', 'Student ($49)', 'Speaker (Complimentary)']], 'validation' => ['required' => true]],
            ['type' => 'checkbox', 'label' => 'Sessions I Plan to Attend', 'name' => 'sessions', 'settings' => ['options' => ['Keynote: Future of AI', 'Workshop: Building with LLMs', 'Panel: Open Source Ecosystem', 'Talk: Scaling Microservices', 'Workshop: DevOps Best Practices', 'Networking Lunch']], 'validation' => ['required' => false]],
            ['type' => 'radio', 'label' => 'Dietary Preference', 'name' => 'dietary', 'settings' => ['options' => ['No restriction', 'Vegetarian', 'Vegan', 'Gluten-Free', 'Halal', 'Kosher']], 'validation' => ['required' => true]],
            ['type' => 'radio', 'label' => 'T-Shirt Size', 'name' => 'tshirt_size', 'settings' => ['options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL']], 'validation' => ['required' => true]],
            ['type' => 'textarea', 'label' => 'Any special requirements or questions?', 'name' => 'notes', 'settings' => ['placeholder' => 'Accessibility needs, questions, etc.', 'rows' => 3], 'validation' => ['required' => false]],
        ];

        foreach ($eventFields as $i => $f) {
            FormField::create([
                'form_id' => $event->id,
                'field_type_id' => $types[$f['type']]->id,
                'label' => $f['label'],
                'name' => $f['name'],
                'order' => $i,
                'settings' => $f['settings'],
                'validation' => $f['validation'],
                'styles' => ['width' => 'full'],
            ]);
        }

        // Submissions for Event
        $eventSubmissions = [
            ['first_name' => 'Rachel', 'last_name' => 'Green', 'email' => 'rachel@fashion.com', 'company' => 'Central Perk Tech', 'job_title' => 'CTO', 'ticket_type' => 'VIP ($499)', 'sessions' => ['Keynote: Future of AI', 'Panel: Open Source Ecosystem', 'Networking Lunch'], 'dietary' => 'Vegetarian', 'tshirt_size' => 'S', 'notes' => ''],
            ['first_name' => 'Ross', 'last_name' => 'Geller', 'email' => 'ross@museum.edu', 'company' => 'NYU Paleontology', 'job_title' => 'Professor', 'ticket_type' => 'General Admission ($199)', 'sessions' => ['Keynote: Future of AI', 'Talk: Scaling Microservices'], 'dietary' => 'No restriction', 'tshirt_size' => 'M', 'notes' => 'Will need a parking pass.'],
            ['first_name' => 'Monica', 'last_name' => 'Geller', 'email' => 'monica@bistro.co', 'company' => 'Bistro Nova', 'job_title' => 'Head Chef / Owner', 'ticket_type' => 'General Admission ($199)', 'sessions' => ['Keynote: Future of AI', 'Workshop: Building with LLMs', 'Networking Lunch'], 'dietary' => 'Gluten-Free', 'tshirt_size' => 'XS', 'notes' => ''],
            ['first_name' => 'Chandler', 'last_name' => 'Bing', 'email' => 'chandler@ads.biz', 'company' => 'BING Advertising', 'job_title' => 'Statistical Analysis', 'ticket_type' => 'VIP ($499)', 'sessions' => ['Workshop: DevOps Best Practices', 'Panel: Open Source Ecosystem', 'Networking Lunch'], 'dietary' => 'No restriction', 'tshirt_size' => 'L', 'notes' => 'Could this BE any more exciting?'],
            ['first_name' => 'Joey', 'last_name' => 'Tribbiani', 'email' => 'joey@acting.la', 'company' => 'Days of Our Lives', 'job_title' => 'Actor', 'ticket_type' => 'Student ($49)', 'sessions' => ['Keynote: Future of AI'], 'dietary' => 'No restriction', 'tshirt_size' => 'XL', 'notes' => 'Will there be sandwiches at the networking lunch?'],
            ['first_name' => 'Phoebe', 'last_name' => 'Buffay', 'email' => 'phoebe@smelly.cat', 'company' => 'Freelance Musician', 'job_title' => 'Artist', 'ticket_type' => 'General Admission ($199)', 'sessions' => ['Workshop: Building with LLMs', 'Talk: Scaling Microservices'], 'dietary' => 'Vegan', 'tshirt_size' => 'S', 'notes' => 'Do the computers run on good vibes?'],
            ['first_name' => 'Elon', 'last_name' => 'Sample', 'email' => 'elon@rocketship.io', 'company' => 'Rocket Dynamics', 'job_title' => 'CEO', 'ticket_type' => 'Speaker (Complimentary)', 'sessions' => ['Keynote: Future of AI', 'Workshop: Building with LLMs', 'Panel: Open Source Ecosystem', 'Talk: Scaling Microservices', 'Workshop: DevOps Best Practices', 'Networking Lunch'], 'dietary' => 'No restriction', 'tshirt_size' => 'M', 'notes' => 'I will need a whiteboard on stage.'],
            ['first_name' => 'Sara', 'last_name' => 'Connor', 'email' => 'sara@resistance.net', 'company' => 'Skynet Prevention LLC', 'job_title' => 'Security Researcher', 'ticket_type' => 'General Admission ($199)', 'sessions' => ['Workshop: Building with LLMs', 'Panel: Open Source Ecosystem'], 'dietary' => 'No restriction', 'tshirt_size' => 'M', 'notes' => 'I have serious concerns about the AI keynote.'],
            ['first_name' => 'Mark', 'last_name' => 'Zuckerberg', 'email' => 'mark@metabook.com', 'company' => 'Meta Book', 'job_title' => 'Human Person CEO', 'ticket_type' => 'VIP ($499)', 'sessions' => ['Keynote: Future of AI', 'Workshop: Building with LLMs', 'Networking Lunch'], 'dietary' => 'No restriction', 'tshirt_size' => 'S', 'notes' => 'I am definitely a human and not a robot.'],
            ['first_name' => 'Priya', 'last_name' => 'Sharma', 'email' => 'priya@devs.in', 'company' => 'InfraStack', 'job_title' => 'Platform Engineer', 'ticket_type' => 'General Admission ($199)', 'sessions' => ['Workshop: DevOps Best Practices', 'Talk: Scaling Microservices'], 'dietary' => 'Halal', 'tshirt_size' => 'S', 'notes' => ''],
            ['first_name' => 'Carlos', 'last_name' => 'Mendez', 'email' => 'carlos@ui.mx', 'company' => 'PixelForge', 'job_title' => 'UI Engineer', 'ticket_type' => 'Student ($49)', 'sessions' => ['Keynote: Future of AI', 'Workshop: Building with LLMs'], 'dietary' => 'Vegetarian', 'tshirt_size' => 'M', 'notes' => 'First tech conference, very excited!'],
        ];

        foreach ($eventSubmissions as $sub) {
            FormSubmission::create([
                'form_id' => $event->id,
                'data' => $sub,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ]);
        }

        // ─── 5. FORM 4: Customer Feedback ────────────────────────────
        $feedback = Form::create([
            'title' => 'Customer Feedback Survey',
            'slug' => 'customer-feedback',
            'description' => 'We value your feedback. Help us improve our product.',
            'status' => 'published',
            'framework' => 'tailwind',
            'settings' => ['success_message' => 'Thank you for your feedback! It helps us build a better product.'],
        ]);

        $feedbackFields = [
            ['type' => 'text', 'label' => 'Your Name (optional)', 'name' => 'name', 'settings' => ['placeholder' => 'Anonymous'], 'validation' => ['required' => false]],
            ['type' => 'email', 'label' => 'Email (optional)', 'name' => 'email', 'settings' => ['placeholder' => 'you@example.com'], 'validation' => ['required' => false]],
            ['type' => 'radio', 'label' => 'Overall Satisfaction', 'name' => 'satisfaction', 'settings' => ['options' => ['⭐ Very Dissatisfied', '⭐⭐ Dissatisfied', '⭐⭐⭐ Neutral', '⭐⭐⭐⭐ Satisfied', '⭐⭐⭐⭐⭐ Very Satisfied']], 'validation' => ['required' => true]],
            ['type' => 'radio', 'label' => 'How likely are you to recommend us?', 'name' => 'nps', 'settings' => ['options' => ['0 – Not at all', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10 – Extremely likely']], 'validation' => ['required' => true]],
            ['type' => 'checkbox', 'label' => 'Which features do you use most?', 'name' => 'features_used', 'settings' => ['options' => ['Form Builder', 'Analytics Dashboard', 'Team Collaboration', 'API Access', 'Webhooks', 'Email Notifications']], 'validation' => ['required' => false]],
            ['type' => 'radio', 'label' => 'How would you rate our support?', 'name' => 'support_rating', 'settings' => ['options' => ['Excellent', 'Good', 'Average', 'Poor', 'Have not contacted support']], 'validation' => ['required' => true]],
            ['type' => 'textarea', 'label' => 'What do you like most?', 'name' => 'likes', 'settings' => ['placeholder' => 'Tell us what you love...', 'rows' => 3], 'validation' => ['required' => false]],
            ['type' => 'textarea', 'label' => 'What can we improve?', 'name' => 'improvements', 'settings' => ['placeholder' => 'Any suggestions or pain points...', 'rows' => 3], 'validation' => ['required' => false]],
            ['type' => 'radio', 'label' => 'Would you be open to a follow-up call?', 'name' => 'follow_up', 'settings' => ['options' => ['Yes, happy to chat', 'Maybe, email me first', 'No, thank you']], 'validation' => ['required' => true]],
        ];

        foreach ($feedbackFields as $i => $f) {
            FormField::create([
                'form_id' => $feedback->id,
                'field_type_id' => $types[$f['type']]->id,
                'label' => $f['label'],
                'name' => $f['name'],
                'order' => $i,
                'settings' => $f['settings'],
                'validation' => $f['validation'],
                'styles' => ['width' => 'full'],
            ]);
        }

        // Submissions for Feedback
        $feedbackSubmissions = [
            ['name' => 'Tom H.', 'email' => 'tom@work.com', 'satisfaction' => '⭐⭐⭐⭐⭐ Very Satisfied', 'nps' => '10 – Extremely likely', 'features_used' => ['Form Builder', 'API Access', 'Webhooks'], 'support_rating' => 'Excellent', 'likes' => 'The drag-and-drop builder is incredibly intuitive. Saved our team hours every week.', 'improvements' => 'Would love a dark mode option.', 'follow_up' => 'Yes, happy to chat'],
            ['name' => 'Anonymous', 'email' => '', 'satisfaction' => '⭐⭐⭐ Neutral', 'nps' => '6', 'features_used' => ['Form Builder'], 'support_rating' => 'Average', 'likes' => 'It works for basic use cases.', 'improvements' => 'The mobile experience needs work. Forms are hard to fill on phones.', 'follow_up' => 'No, thank you'],
            ['name' => 'Lisa M.', 'email' => 'lisa@agency.co', 'satisfaction' => '⭐⭐⭐⭐ Satisfied', 'nps' => '8', 'features_used' => ['Form Builder', 'Analytics Dashboard', 'Email Notifications'], 'support_rating' => 'Good', 'likes' => 'Submissions dashboard is clean and easy to share with clients.', 'improvements' => 'Conditional logic for fields would be a game changer.', 'follow_up' => 'Maybe, email me first'],
            ['name' => 'Derek P.', 'email' => 'derek@dev.io', 'satisfaction' => '⭐⭐⭐⭐⭐ Very Satisfied', 'nps' => '10 – Extremely likely', 'features_used' => ['API Access', 'Webhooks', 'Team Collaboration'], 'support_rating' => 'Excellent', 'likes' => 'The API is solid and well-documented. Integrated into our pipeline in an afternoon.', 'improvements' => 'Rate limits could be more generous on the growth plan.', 'follow_up' => 'Yes, happy to chat'],
            ['name' => 'Anonymous', 'email' => '', 'satisfaction' => '⭐ Very Dissatisfied', 'nps' => '0 – Not at all', 'features_used' => ['Form Builder'], 'support_rating' => 'Poor', 'likes' => 'Nothing stands out.', 'improvements' => 'The product crashed twice during an important submission period. Reliability is a must.', 'follow_up' => 'No, thank you'],
            ['name' => 'Nadia S.', 'email' => 'nadia@hr.com', 'satisfaction' => '⭐⭐⭐⭐ Satisfied', 'nps' => '7', 'features_used' => ['Form Builder', 'Analytics Dashboard'], 'support_rating' => 'Good', 'likes' => 'Using it for HR forms and it covers everything we need.', 'improvements' => 'Export to Excel would be very helpful.', 'follow_up' => 'Maybe, email me first'],
            ['name' => 'Ryan O.', 'email' => 'ryan@startup.io', 'satisfaction' => '⭐⭐⭐⭐⭐ Very Satisfied', 'nps' => '9', 'features_used' => ['Form Builder', 'Webhooks', 'Email Notifications'], 'support_rating' => 'Excellent', 'likes' => 'Webhook support is flawless. We pipe responses straight into our CRM.', 'improvements' => 'More built-in field types like signature and payment.', 'follow_up' => 'Yes, happy to chat'],
        ];

        foreach ($feedbackSubmissions as $sub) {
            FormSubmission::create([
                'form_id' => $feedback->id,
                'data' => $sub,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ]);
        }

        // ─── 6. FORM 5: Newsletter Signup (Draft) ────────────────────
        $newsletter = Form::create([
            'title' => 'Newsletter Signup',
            'slug' => 'newsletter-signup',
            'description' => 'Stay up to date with our latest news and product updates.',
            'status' => 'draft',
            'framework' => 'tailwind',
            'settings' => ['success_message' => 'You are subscribed! Welcome aboard.'],
        ]);

        $newsletterFields = [
            ['type' => 'text', 'label' => 'First Name', 'name' => 'first_name', 'settings' => ['placeholder' => 'Jane'], 'validation' => ['required' => true]],
            ['type' => 'email', 'label' => 'Email', 'name' => 'email', 'settings' => ['placeholder' => 'jane@example.com'], 'validation' => ['required' => true]],
            ['type' => 'checkbox', 'label' => 'I am interested in', 'name' => 'interests', 'settings' => ['options' => ['Product Updates', 'Company News', 'Tutorials & Tips', 'Industry Insights']], 'validation' => ['required' => false]],
        ];

        foreach ($newsletterFields as $i => $f) {
            FormField::create([
                'form_id' => $newsletter->id,
                'field_type_id' => $types[$f['type']]->id,
                'label' => $f['label'],
                'name' => $f['name'],
                'order' => $i,
                'settings' => $f['settings'],
                'validation' => $f['validation'],
                'styles' => ['width' => 'full'],
            ]);
        }
        // No submissions — it's a draft

        $this->command->info('✅  Dummy data seeded successfully!');
        $this->command->table(
            ['Form', 'Slug', 'Status', 'Fields', 'Submissions'],
            [
                [$contact->title, $contact->slug, $contact->status, count($contactFields), count($contactSubmissions)],
                [$job->title, $job->slug, $job->status, count($jobFields), count($jobSubmissions)],
                [$event->title, $event->slug, $event->status, count($eventFields), count($eventSubmissions)],
                [$feedback->title, $feedback->slug, $feedback->status, count($feedbackFields), count($feedbackSubmissions)],
                [$newsletter->title, $newsletter->slug, $newsletter->status, count($newsletterFields), 0],
            ]
        );
    }

}
