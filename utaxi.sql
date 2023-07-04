-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 02-Jul-2023 às 03:32
-- Versão do servidor: 5.7.24
-- versão do PHP: 8.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `utaxi`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `admin`
--

INSERT INTO `admin` (`id`, `nome`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Adriano Ernesto Evaristo', 'angolawap@gmail.com', '$2y$10$CpJpVe59E15yQyKwkjXwG.PVCHTUyjMZserJzvZ6MXWoZU4t46YQW', NULL, '2023-06-27 21:48:45', '2023-06-27 21:48:45');

-- --------------------------------------------------------

--
-- Estrutura da tabela `carros`
--

CREATE TABLE `carros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `velocidade_media` int(11) NOT NULL,
  `posicao_y` int(11) NOT NULL DEFAULT '1',
  `posicao_x` int(11) NOT NULL DEFAULT '1',
  `motorista_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `preco` decimal(32,2) NOT NULL,
  `fiabilidade` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `carros`
--

INSERT INTO `carros` (`id`, `nome`, `modelo`, `tipo`, `velocidade_media`, `posicao_y`, `posicao_x`, `motorista_id`, `created_at`, `updated_at`, `preco`, `fiabilidade`) VALUES
(1, 'A', 'Toyota', 'Carros ligeiros', 10, 2, 2, 0, '2023-06-28 00:11:57', '2023-07-01 19:37:30', '4000.00', 10),
(2, 'B', 'Patrol', 'Carrinha de novo lugar', 200, 0, 0, 1, '2023-06-28 00:16:46', '2023-07-01 19:36:40', '5000.00', 10),
(3, 'C', 'Land', 'Moto', 6, 0, 0, 2, '2023-06-28 00:16:55', '2023-07-01 19:37:26', '6000.00', 10),
(4, 'D', 'Gipe', 'Carrinha de novo lugar', 10, 0, 0, 5, '2023-06-28 08:32:57', '2023-07-01 19:36:34', '7000.00', 10),
(5, 'BMW', 'Yunday 10', 'Carrinha de novo lugar', 10, 10, 10, 0, '2023-06-28 10:26:36', '2023-07-01 19:36:30', '5000.00', 10),
(6, 'Carrom 3', 'Yundai', 'Carros ligeiros', 100, 0, 0, 0, '2023-07-01 19:33:20', '2023-07-01 19:36:21', '100.00', 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2023_06_27_212427_motoristas', 1),
(4, '2023_06_27_213052_viagens', 1),
(5, '2023_06_27_214208_carros', 1),
(6, '2023_06_27_222050_admin', 2),
(8, '2023_06_28_102545_change_carros', 3),
(10, '2023_06_28_192238_change_viagens', 4),
(11, '2023_06_28_224928_change_viagens2', 5),
(12, '2023_07_01_212247_change_carros', 6),
(13, '2023_07_01_214940_change_motoristas', 7),
(14, '2023_07_01_221104_change_viagens', 8),
(15, '2023_07_02_003113_change_motorista', 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `motorista`
--

CREATE TABLE `motorista` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `morada` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_nascimento` timestamp NOT NULL,
  `posicao_x` int(11) NOT NULL,
  `posicao_y` int(11) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `classificacao` int(11) NOT NULL DEFAULT '0',
  `km_realizado` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cumprimento` decimal(8,2) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `motorista`
--

INSERT INTO `motorista` (`id`, `nome`, `email`, `password`, `morada`, `data_nascimento`, `posicao_x`, `posicao_y`, `remember_token`, `created_at`, `updated_at`, `classificacao`, `km_realizado`, `cumprimento`, `estado`) VALUES
(1, 'Actividades', 'adrianoernestoevarist12@gmail.com', '$2y$10$mxxJ7QsrzDpICiFHDOYcROt1VD9QKLVmVD5ru4xZZs9P7nFH4VcWS', 'Luanda, Angola', '2023-06-11 23:00:00', 2, 2, NULL, '2023-06-27 23:48:01', '2023-06-27 23:48:01', 0, '0.00', NULL, 0),
(2, 'Adriano Ernesto Evaristo', 'adrianoernestoevaristo@gmail.com', '$2y$10$dZp1PNLKczuNsl8Wz8MGLuIQlg3GrDctdAPbvqx9xHHCKumgiR6gy', 'Luanda, Angola', '2023-06-18 23:00:00', 2, 2, NULL, '2023-06-28 08:35:20', '2023-06-28 08:35:20', 0, '0.00', NULL, 0),
(5, 'Despachantes', 'tingoshi@gmail.com', '$2y$10$yeVBY6t/XNqslMuJEKis1u0lwf/UPPWJ3yL36X0/.UqKoJNsuZ6ce', 'Wap Angola', '2023-06-13 23:00:00', 2, 2, NULL, '2023-06-28 08:41:38', '2023-07-02 01:17:10', 113, '4.24', NULL, 0),
(6, 'AXR', 'axr@gmail.com', '$2y$10$pm3BAVPd6mgiy6F4iUvzY.k5JfLws23tNdEtKleYhVxCzLHOKeyGm', 'Luanda, Angola', '2023-07-01 23:00:00', 1, 1, NULL, '2023-07-02 01:10:56', '2023-07-02 01:12:38', 0, '0.00', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `morada` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_nascimento` timestamp NOT NULL,
  `posicao_x` int(11) NOT NULL,
  `posicao_y` int(11) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `nome`, `email`, `password`, `morada`, `data_nascimento`, `posicao_x`, `posicao_y`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Adriano Ernesto Evaristo', 'angolawap@gmail.com', '$2y$10$lpeX89WGi5/EEQ44DGPi5eb.A./ORGpCmUa3ILad49ANe/xwemz5i', 'Luanda, Angola', '2023-06-13 23:00:00', 0, 0, NULL, '2023-06-28 09:16:49', '2023-06-28 09:16:49'),
(4, 'Adrx Ernestx', 'gera@guro.ao', '$2y$10$6cEaaqAIL4/4d44fa.LLNOAF2ywgbnMNQVahb1cO2xZX45S9O5SBu', 'Luanda, Angola', '2023-06-14 23:00:00', 0, 0, NULL, '2023-06-28 23:33:56', '2023-06-28 23:33:56'),
(5, 'xxx xxxx', 'xxx@gmail.com', '$2y$10$wFiDMmWPbuqCAqVDdHJf4OWMuxutIsjkoBXKxr5rVNuX.f.LXMKOG', 'Morada Angola', '2023-07-11 23:00:00', 1, 1, NULL, '2023-07-02 00:57:59', '2023-07-02 00:57:59'),
(6, 'Motorista 1', 'waptt@gmail.com', '$2y$10$S6yd.xRlfrWQO1cXx/NSUOUmdrm6y4dnqT8UyeR6GiJ7Im64O1Li6', 'Morada Angola', '2023-07-01 23:00:00', 1, 1, NULL, '2023-07-02 01:04:43', '2023-07-02 01:04:43'),
(7, 'Utaxi', 'tu@gmail.com', '$2y$10$aPIr.gxnIcsMfzyNcBtx9usX6EeO6PVJd0n.cCDACdUybYpaVJIX6', 'Wef', '2023-07-01 23:00:00', 1, 1, NULL, '2023-07-02 01:06:15', '2023-07-02 01:06:15'),
(8, 'UxE', 'ufa@gmail.com', '$2y$10$vx698h87DTX.UzrmKXnkc.auNCd9uFcxlOnfrBmBCnoXkrpmaBUCC', 'Wap Angola', '2023-07-01 23:00:00', 1, 1, NULL, '2023-07-02 01:10:04', '2023-07-02 01:10:04');

-- --------------------------------------------------------

--
-- Estrutura da tabela `viagens`
--

CREATE TABLE `viagens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `posicao_x` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posicao_y` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `motorista_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `preco` decimal(32,2) DEFAULT NULL,
  `cliente_id` bigint(20) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '0',
  `km` decimal(8,2) DEFAULT NULL,
  `tempo_estimado` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `viagens`
--

INSERT INTO `viagens` (`id`, `posicao_x`, `posicao_y`, `motorista_id`, `created_at`, `updated_at`, `preco`, `cliente_id`, `estado`, `km`, `tempo_estimado`) VALUES
(1, '1', '1', '2', '2023-06-28 18:55:08', '2023-06-28 22:15:17', '10000.00', 3, 1, NULL, NULL),
(2, '0', '0', '2', '2023-06-28 23:10:53', '2023-06-28 23:11:16', '6000.00', 3, 1, NULL, NULL),
(3, '1', '1', '2', '2023-06-28 23:44:15', '2023-06-29 00:17:55', '6000.00', 3, 1, NULL, NULL),
(4, '1', '1', '2', '2023-06-29 00:01:38', '2023-06-29 00:01:38', '6000.00', 4, 2, NULL, NULL),
(5, '1', '1', '2', '2023-06-29 00:41:35', '2023-06-29 00:42:56', '8000.00', 3, 1, NULL, NULL),
(6, '10', '10', '5', '2023-07-01 21:08:46', '2023-07-01 22:26:57', '19798.99', 3, 1, '2.83', NULL),
(7, '1', '1', '5', '2023-07-02 01:16:38', '2023-07-02 01:16:55', '9899.49', 3, 1, '1.41', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_email_unique` (`email`);

--
-- Índices para tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `motorista`
--
ALTER TABLE `motorista`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `motorista_email_unique` (`email`);

--
-- Índices para tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Índices para tabela `viagens`
--
ALTER TABLE `viagens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `carros`
--
ALTER TABLE `carros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `motorista`
--
ALTER TABLE `motorista`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `viagens`
--
ALTER TABLE `viagens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
