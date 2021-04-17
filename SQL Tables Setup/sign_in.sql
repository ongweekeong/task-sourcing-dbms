CREATE TABLE sign_in (
	email VARCHAR(64) NOT NULL,
	password VARCHAR(32) NOT NULL,
	FOREIGN KEY (email, password) REFERENCES user_info(email, password) ON DELETE CASCADE
);