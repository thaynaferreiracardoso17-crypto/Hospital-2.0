-- Hospital Vida Plena - banco de dados (MySQL / MariaDB)
CREATE DATABASE IF NOT EXISTS hospital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hospital;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  telefone VARCHAR(11) NOT NULL,
  endereco VARCHAR(255) NOT NULL,
  senha_hash VARCHAR(255) NOT NULL,
  perfil ENUM('paciente','admin') NOT NULL DEFAULT 'paciente',
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS especialidades (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(80) NOT NULL UNIQUE,
  descricao VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS medicos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  crm VARCHAR(20) NOT NULL UNIQUE,
  especialidade_id INT UNSIGNED NOT NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (especialidade_id) REFERENCES especialidades(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS consultas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  medico_id INT UNSIGNED NOT NULL,
  data_hora DATETIME NOT NULL,
  motivo VARCHAR(255) NOT NULL,
  status ENUM('agendada','realizada','cancelada') NOT NULL DEFAULT 'agendada',
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE RESTRICT,
  INDEX idx_medico_data (medico_id, data_hora)
) ENGINE=InnoDB;

INSERT IGNORE INTO especialidades (nome, descricao) VALUES
 ('Clínica Geral', 'Avaliação geral da saúde, prevenção e acompanhamento.'),
 ('Cardiologia', 'Cuidado do coração e da circulação.'),
 ('Pediatria', 'Atendimento de bebês, crianças e adolescentes.'),
 ('Ortopedia', 'Ossos, articulações, músculos e lesões esportivas.'),
 ('Ginecologia', 'Saúde da mulher, pré-natal e prevenção.'),
 ('Dermatologia', 'Pele, cabelos e unhas.');

INSERT IGNORE INTO medicos (nome, crm, especialidade_id, ativo) VALUES
 ('Dra. Helena Souza', 'CRM-SP 100001', 1, 1),
 ('Dr. Marcos Pereira', 'CRM-SP 100002', 2, 1),
 ('Dra. Camila Torres', 'CRM-SP 100003', 3, 1),
 ('Dr. Rafael Lima', 'CRM-SP 100004', 4, 1),
 ('Dra. Beatriz Nunes', 'CRM-SP 100005', 5, 1),
 ('Dr. André Martins', 'CRM-SP 100006', 6, 1);

-- O administrador é criado pelo script criar_admin.php (a senha precisa de hash).
