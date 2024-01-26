-- Create the 'users' table in PostgreSQL
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    user_type VARCHAR(20) NOT NULL CHECK (user_type IN ('employer', 'jobseeker'))
);

CREATE TABLE eprofile (
    eprofile_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id) UNIQUE,
    company_name VARCHAR(255),
    industry VARCHAR(255),
    about_company TEXT
);
CREATE TABLE jprofile (
    user_id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    age INTEGER,
    qualification VARCHAR(100),
    experience VARCHAR(100),
    skills VARCHAR(255),
    -- Add more fields as needed
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create a foreign key constraint to link the jprofile table with the users table
ALTER TABLE jprofile
ADD CONSTRAINT fk_user_id
FOREIGN KEY (user_id)
REFERENCES users(user_id);



CREATE TABLE jobs (
    job_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    job_title VARCHAR(255) NOT NULL,
    job_description TEXT NOT NULL,
    job_requirements TEXT NOT NULL,
    job_position VARCHAR(255) NOT NULL,
    salary VARCHAR(255) NOT NULL,
    other_details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE applications (
    application_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (job_id) REFERENCES jobs(job_id)
);

-- Alter the applications table to add ON DELETE CASCADE
ALTER TABLE applications
DROP CONSTRAINT applications_job_id_fkey, -- Drop the existing foreign key constraint if it exists
ADD CONSTRAINT applications_job_id_fkey
FOREIGN KEY (job_id)
REFERENCES jobs(job_id)
ON DELETE CASCADE;





-- Create the trigger function
CREATE OR REPLACE FUNCTION prevent_duplicate_applications()
RETURNS TRIGGER AS $$
BEGIN
   IF EXISTS (
      SELECT 1
      FROM applications
      WHERE user_id = NEW.user_id AND job_id = NEW.job_id
   ) THEN
      RAISE EXCEPTION 'Duplicate application not allowed';
   END IF;
   RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger
CREATE TRIGGER before_insert_application
BEFORE INSERT ON applications
FOR EACH ROW
EXECUTE FUNCTION prevent_duplicate_applications();

ALTER TABLE applications
ADD CONSTRAINT unique_application UNIQUE (user_id, job_id);



-- Trigger function to enforce email uniqueness across users, eprofile, and jprofile tables
CREATE OR REPLACE FUNCTION enforce_email_uniqueness()
RETURNS TRIGGER AS $$
DECLARE
    email_count INTEGER;
BEGIN
    -- Check if the email already exists in users, eprofile, or jprofile
    SELECT COUNT(*) INTO email_count
    FROM (
        SELECT email FROM users WHERE NEW.email = email
        UNION ALL
        SELECT email FROM eprofile WHERE NEW.email = email
        UNION ALL
        SELECT email FROM jprofile WHERE NEW.email = email
    ) AS email_union;

    -- If email_count is greater than 1, it means the email already exists
    IF email_count > 1 THEN
        RAISE EXCEPTION 'Email address must be unique across users, eprofile, and jprofile tables.';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION validate_salary_format()
RETURNS TRIGGER AS $$
BEGIN
    IF NOT (NEW.salary ~ '^[A-Z]{3}\s\d+(\.\d{1,2})?\sper\s(year|month)$') THEN
        RAISE EXCEPTION 'Invalid salary format. Use format like "USD 50000 per year".';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER validate_salary_format_trigger
BEFORE INSERT OR UPDATE ON jobs
FOR EACH ROW
EXECUTE FUNCTION validate_salary_format();


CREATE OR REPLACE FUNCTION job_title_length()
RETURNS TRIGGER AS $$
BEGIN
    IF LENGTH(NEW.job_title) > 20 THEN
        RAISE EXCEPTION 'Job title cannot exceed 20 characters.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER job_title_length_trigger
BEFORE INSERT OR UPDATE ON jobs
FOR EACH ROW
EXECUTE FUNCTION job_title_length();








