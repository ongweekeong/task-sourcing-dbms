CREATE TABLE log_task (
log_name VARCHAR(64) NOT NULL,
log_location VARCHAR(128) NOT NULL,
log_task_datetime TIMESTAMP WITHOUT TIME ZONE NOT NULL,
log_max_amt MONEY,
log_tasker_email VARCHAR(64) NOT NULL,
log_deadline TIMESTAMP WITHOUT TIME ZONE,
log_submission_datetime NUMERIC NOT NULL,
log_task_status task_status NOT NULL,
log_task_doer VARCHAR(64),
log_bid_amt MONEY
);

