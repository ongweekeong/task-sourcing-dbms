CREATE TABLE task_submission (
	name VARCHAR(64) NOT NULL,
	description VARCHAR(512),
	location VARCHAR(128) NOT NULL,
	end_location VARCHAR(128),
	task_datetime TIMESTAMP WITHOUT TIME ZONE NOT NULL,
	max_amt MONEY,
	email VARCHAR(64) NOT NULL,
	deadline TIMESTAMP WITHOUT TIME ZONE CHECK (deadline <= task_datetime),
	submission_datetime NUMERIC NOT NULL,
	end_datetime TIMESTAMP CHECK (end_datetime >= task_datetime),
	task_status TASK_STATUS NOT NULL DEFAULT 'in progress',
	task_doer VARCHAR(64),
	FOREIGN KEY (email) REFERENCES user_info(email) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY(submission_datetime, email)
);