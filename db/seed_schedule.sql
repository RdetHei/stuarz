-- Seed data for classes, teachers (users), and schedules
USE stuarz;

-- Create 3 classes (ignore if exist via distinct codes)
INSERT INTO classes (name, code, description, created_by)
SELECT * FROM (
  SELECT 'X IPA 1' AS name, 'XIPA1' AS code, 'Kelas X IPA 1' AS description, 5 AS created_by
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM classes WHERE code='XIPA1');

INSERT INTO classes (name, code, description, created_by)
SELECT * FROM (
  SELECT 'XI IPS 2', 'XIIPS2', 'Kelas XI IPS 2', 5
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM classes WHERE code='XIIPS2');

INSERT INTO classes (name, code, description, created_by)
SELECT * FROM (
  SELECT 'XII RPL 3', 'XIIRPL3', 'Kelas XII RPL 3', 5
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM classes WHERE code='XIIRPL3');

-- Create 2 teachers as users with level='user'
INSERT INTO users (username, email, password, level, name, join_date)
SELECT * FROM (
  SELECT 'guru1', 'guru1@example.com', '$2y$10$0v8Vnq8aTnJ9w2mV9w9Wte0s0vG3nZKqv8X0uHnqA3f2j3m4l5p6S', 'user', 'Guru Satu', CURDATE()
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username='guru1');

INSERT INTO users (username, email, password, level, name, join_date)
SELECT * FROM (
  SELECT 'guru2', 'guru2@example.com', '$2y$10$0v8Vnq8aTnJ9w2mV9w9Wte0s0vG3nZKqv8X0uHnqA3f2j3m4l5p6S', 'user', 'Guru Dua', CURDATE()
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username='guru2');

-- Resolve class ids
SET @cid1 = (SELECT id FROM classes WHERE code='XIPA1');
SET @cid2 = (SELECT id FROM classes WHERE code='XIIPS2');
SET @cid3 = (SELECT id FROM classes WHERE code='XIIRPL3');
SET @t1 = (SELECT id FROM users WHERE username='guru1');
SET @t2 = (SELECT id FROM users WHERE username='guru2');

-- Insert 6 schedules (Mon-Sat)
INSERT INTO schedule (`class`, `subject`, `teacher_id`, `class_id`, `day`, `start_time`, `end_time`)
SELECT * FROM (
  SELECT 'Ruang 101', 'Matematika', @t1, @cid1, 'Senin', '07:00:00', '08:40:00'
) AS tmp
WHERE NOT EXISTS (
  SELECT 1 FROM schedule WHERE class_id=@cid1 AND day='Senin' AND subject='Matematika'
);

INSERT INTO schedule (`class`, `subject`, `teacher_id`, `class_id`, `day`, `start_time`, `end_time`)
SELECT * FROM (
  SELECT 'Ruang 102', 'Bahasa Indonesia', @t2, @cid2, 'Selasa', '08:50:00', '10:30:00'
) AS tmp
WHERE NOT EXISTS (
  SELECT 1 FROM schedule WHERE class_id=@cid2 AND day='Selasa' AND subject='Bahasa Indonesia'
);

INSERT INTO schedule (`class`, `subject`, `teacher_id`, `class_id`, `day`, `start_time`, `end_time`)
SELECT * FROM (
  SELECT 'Lab Komputer 1', 'Informatika', @t1, @cid3, 'Rabu', '09:00:00', '10:40:00'
) AS tmp
WHERE NOT EXISTS (
  SELECT 1 FROM schedule WHERE class_id=@cid3 AND day='Rabu' AND subject='Informatika'
);

INSERT INTO schedule (`class`, `subject`, `teacher_id`, `class_id`, `day`, `start_time`, `end_time`)
SELECT * FROM (
  SELECT 'Ruang 201', 'Fisika', @t2, @cid1, 'Kamis', '07:00:00', '08:40:00'
) AS tmp
WHERE NOT EXISTS (
  SELECT 1 FROM schedule WHERE class_id=@cid1 AND day='Kamis' AND subject='Fisika'
);

INSERT INTO schedule (`class`, `subject`, `teacher_id`, `class_id`, `day`, `start_time`, `end_time`)
SELECT * FROM (
  SELECT 'Ruang 202', 'Ekonomi', @t1, @cid2, 'Jumat', '08:50:00', '10:30:00'
) AS tmp
WHERE NOT EXISTS (
  SELECT 1 FROM schedule WHERE class_id=@cid2 AND day='Jumat' AND subject='Ekonomi'
);

INSERT INTO schedule (`class`, `subject`, `teacher_id`, `class_id`, `day`, `start_time`, `end_time`)
SELECT * FROM (
  SELECT 'Lab Komputer 2', 'Praktikum RPL', @t2, @cid3, 'Sabtu', '09:00:00', '10:40:00'
) AS tmp
WHERE NOT EXISTS (
  SELECT 1 FROM schedule WHERE class_id=@cid3 AND day='Sabtu' AND subject='Praktikum RPL'
);


