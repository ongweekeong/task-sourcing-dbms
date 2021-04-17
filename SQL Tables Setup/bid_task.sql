CREATE TABLE bid_task (
	tasker_email VARCHAR(64) NOT NULL,
	submission_datetime NUMERIC NOT NULL,
	bidder_email VARCHAR(64) NOT NULL,
	bid_amt MONEY NOT NULL,
	bid_datetime TIMESTAMP WITHOUT TIME ZONE NOT NULL,
	bid_status bid_status NOT NULL DEFAULT 'in progress',
	FOREIGN KEY(submission_datetime, tasker_email) REFERENCES task_submission(submission_datetime, email) ON DELETE CASCADE,
	FOREIGN KEY(bidder_email) REFERENCES user_info(email) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY(tasker_email, submission_datetime, bidder_email)
);

