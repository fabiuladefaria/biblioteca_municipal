/* 1) TABELA USUARIO */
CREATE TABLE Usuario (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senhaHash VARCHAR(255) NOT NULL,
    tipo VARCHAR(20) NOT NULL DEFAULT 'usuario',

    telefone VARCHAR(20),
    endereco VARCHAR(200),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    cep VARCHAR(15),
    dataNascimento DATE,
    fotoUrl VARCHAR(300),

    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expira DATETIME DEFAULT NULL,
    tokenRecuperacao VARCHAR(255) DEFAULT NULL,
    tokenApi VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

/* 2) TABELA LIVRO */
CREATE TABLE Livro (
    idLivro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150),
    descricao TEXT,
    categoria VARCHAR(50),
    status VARCHAR(20) DEFAULT 'disponivel',
    capaUrl VARCHAR(300)
) ENGINE=InnoDB;

/* 3) TABELA EMPRESTIMO */
CREATE TABLE Emprestimo (
    idEmprestimo INT AUTO_INCREMENT PRIMARY KEY,
    idLivro INT NOT NULL,
    idUsuario INT NOT NULL,
    dataEmprestimo DATE NOT NULL,
    dataDevolucaoPrevista DATE NOT NULL,
    dataDevolucaoReal DATE NULL,
    
    FOREIGN KEY (idLivro) REFERENCES Livro(idLivro)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

/* 4) TABELA AVALIACAO */
CREATE TABLE Avaliacao (
    idAvaliacao INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT NOT NULL,
    idLivro INT NOT NULL,
    estrelas INT NOT NULL,
    comentario TEXT,
    dataAvaliacao DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idLivro) REFERENCES Livro(idLivro)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

/* 5) INSERIR ADMINS */
INSERT INTO Usuario (nome, email, senhaHash, tipo)
VALUES (
    'Administrador',
    'admin@biblioteca.com',
    '$2y$10$1QSlQCNysWpjS2Chu8oZye4yCdKOY2wgwbXEOVC2SfZpOowhP0dL2',
    'admin'
);

INSERT INTO Usuario (nome, email, senhaHash, tipo)
VALUES (
    'Administrador Alfenas',
    'adminalfenas@biblioteca.com',
    '$2y$10$NqjGPWN5fEKSMYWxUmeK1OwWM4T1IjhV5iVCEsIpLJZkoNUP1sfpK',
    'admin'
);

/* Alterar senha do admin Alfenas */
UPDATE Usuario
SET senhaHash = '$2y$10$ZEkRBBDHnEdm67m8W7jwveeNjGBqajFoBsP2FSNRfN0My6YPQAaaC'
WHERE email = 'adminalfenas@biblioteca.com';
