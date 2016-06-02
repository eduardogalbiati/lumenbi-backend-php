USE [Silex-teste]
GO
/****** Object:  Table [dbo].[Menu]    Script Date: 06/16/2014 20:28:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[Menu](
	[idMenu] [int] IDENTITY(1,1) NOT NULL,
	[idMenuPai] [int] NOT NULL,
	[nomeMenu] [varchar](80) NULL,
	[nomeModulo] [varchar](80) NULL,
	[defaultPath] [varchar](80) NULL,
 CONSTRAINT [PK_Menu] PRIMARY KEY CLUSTERED 
(
	[idMenu] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
SET IDENTITY_INSERT [dbo].[Menu] ON
INSERT [dbo].[Menu] ([idMenu], [idMenuPai], [nomeMenu], [nomeModulo], [defaultPath]) VALUES (1, 0, N'Início', N'index', N'.')
INSERT [dbo].[Menu] ([idMenu], [idMenuPai], [nomeMenu], [nomeModulo], [defaultPath]) VALUES (2, 0, N'Gerenciar Usuários', N'gerenciarUsuarios', N'#')
INSERT [dbo].[Menu] ([idMenu], [idMenuPai], [nomeMenu], [nomeModulo], [defaultPath]) VALUES (3, 2, N'Departamentos', N'departments', N'/departaments/crud/listView')
INSERT [dbo].[Menu] ([idMenu], [idMenuPai], [nomeMenu], [nomeModulo], [defaultPath]) VALUES (4, 2, N'Usuários', N'users', N'/users/crud/editView')
SET IDENTITY_INSERT [dbo].[Menu] OFF
/****** Object:  Table [dbo].[Empresas]    Script Date: 06/16/2014 20:28:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[Empresas](
	[idEmpresa] [int] IDENTITY(1,1) NOT NULL,
	[nomeEmpresa] [varchar](255) NULL,
 CONSTRAINT [PK_Empresas] PRIMARY KEY CLUSTERED 
(
	[idEmpresa] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
SET IDENTITY_INSERT [dbo].[Empresas] ON
INSERT [dbo].[Empresas] ([idEmpresa], [nomeEmpresa]) VALUES (1, N'Cobway')
SET IDENTITY_INSERT [dbo].[Empresas] OFF
/****** Object:  Table [dbo].[Departamentos]    Script Date: 06/16/2014 20:28:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[Departamentos](
	[idDepartamento] [int] IDENTITY(1,1) NOT NULL,
	[nomeDepartamento] [varchar](255) NULL,
 CONSTRAINT [PK_Departamentos] PRIMARY KEY CLUSTERED 
(
	[idDepartamento] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
SET IDENTITY_INSERT [dbo].[Departamentos] ON
INSERT [dbo].[Departamentos] ([idDepartamento], [nomeDepartamento]) VALUES (1, N'Super Administrador')
SET IDENTITY_INSERT [dbo].[Departamentos] OFF
/****** Object:  Table [dbo].[PermissoesTipos]    Script Date: 06/16/2014 20:28:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[PermissoesTipos](
	[idPermissaoTipo] [int] IDENTITY(1,1) NOT NULL,
	[nomePermissaoTipo] [varchar](255) NULL,
 CONSTRAINT [PK_PermissoesTipos] PRIMARY KEY CLUSTERED 
(
	[idPermissaoTipo] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
SET IDENTITY_INSERT [dbo].[PermissoesTipos] ON
INSERT [dbo].[PermissoesTipos] ([idPermissaoTipo], [nomePermissaoTipo]) VALUES (1, N'Visualizar')
INSERT [dbo].[PermissoesTipos] ([idPermissaoTipo], [nomePermissaoTipo]) VALUES (2, N'Cadastrar')
INSERT [dbo].[PermissoesTipos] ([idPermissaoTipo], [nomePermissaoTipo]) VALUES (3, N'Alterar')
INSERT [dbo].[PermissoesTipos] ([idPermissaoTipo], [nomePermissaoTipo]) VALUES (4, N'Excluir')
SET IDENTITY_INSERT [dbo].[PermissoesTipos] OFF
/****** Object:  Table [dbo].[Usuarios]    Script Date: 06/16/2014 20:28:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[Usuarios](
	[idUsuario] [int] IDENTITY(1,1) NOT NULL,
	[idEmpresa] [int] NULL,
	[idDepartamento] [int] NULL,
	[idPerfil] [int] NULL,
	[idDiscador] [int] NULL,
	[nomeUsuario] [varchar](255) NULL,
	[usuario] [varchar](50) NULL,
	[senha] [varchar](255) NULL,
	[email] [varchar](255) NULL,
	[dtNascimento] [date] NULL,
	[cpf] [varchar](11) NULL,
	[rg] [varchar](15) NULL,
	[status] [char](1) NULL,
	[telefone1] [varchar](15) NULL,
	[telefone2] [varchar](15) NULL,
	[cep] [varchar](9) NULL,
	[endereco] [varchar](255) NULL,
	[numero] [varchar](50) NULL,
	[complemento] [varchar](255) NULL,
	[bairro] [varchar](50) NULL,
	[uf] [char](2) NULL,
	[cidade] [varchar](80) NULL,
	[observacoes] [text] NULL,
 CONSTRAINT [PK_Usuarios] PRIMARY KEY CLUSTERED 
(
	[idUsuario] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
SET IDENTITY_INSERT [dbo].[Usuarios] ON
INSERT [dbo].[Usuarios] ([idUsuario], [idEmpresa], [idDepartamento], [idPerfil], [idDiscador], [nomeUsuario], [usuario], [senha], [email], [dtNascimento], [cpf], [rg], [status], [telefone1], [telefone2], [cep], [endereco], [numero], [complemento], [bairro], [uf], [cidade], [observacoes]) VALUES (1, 1, 1, 3, NULL, N'Eduardo Galbiati', N'edu', N'5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', N'eduardo.galbiati7@gmail.com', CAST(0x01380B00 AS Date), N'33997280851', N'445822387', N'1', N'1498145025', N'122451', N'03524-010', N'Rua Pais de Linhares', N'501', N'', N'Jd. Maringá', N'SP', N'São Paulo', N'Primeiro usuário de teste')
INSERT [dbo].[Usuarios] ([idUsuario], [idEmpresa], [idDepartamento], [idPerfil], [idDiscador], [nomeUsuario], [usuario], [senha], [email], [dtNascimento], [cpf], [rg], [status], [telefone1], [telefone2], [cep], [endereco], [numero], [complemento], [bairro], [uf], [cidade], [observacoes]) VALUES (6, 1, 1, 3, NULL, N'Novo Usuario', N'user', N'0beec7b5ea3f0fdbc95d0dd47f3c5bc275da8a33', N'eduardo.galbiati7@gmail.com', CAST(0x01380B00 AS Date), N'', N'', N'1', N'', N'', N'', N'', N'', N'', N'', N'  ', N'', N'asdasd')
SET IDENTITY_INSERT [dbo].[Usuarios] OFF
/****** Object:  Table [dbo].[Permissoes]    Script Date: 06/16/2014 20:28:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Permissoes](
	[idDepartamento] [int] NOT NULL,
	[idPermissaoTipo] [int] NOT NULL,
	[idMenu] [int] NOT NULL
) ON [PRIMARY]
GO
INSERT [dbo].[Permissoes] ([idDepartamento], [idPermissaoTipo], [idMenu]) VALUES (1, 1, 1)
INSERT [dbo].[Permissoes] ([idDepartamento], [idPermissaoTipo], [idMenu]) VALUES (1, 2, 1)
INSERT [dbo].[Permissoes] ([idDepartamento], [idPermissaoTipo], [idMenu]) VALUES (1, 3, 1)
INSERT [dbo].[Permissoes] ([idDepartamento], [idPermissaoTipo], [idMenu]) VALUES (1, 4, 1)
INSERT [dbo].[Permissoes] ([idDepartamento], [idPermissaoTipo], [idMenu]) VALUES (1, 1, 2)
INSERT [dbo].[Permissoes] ([idDepartamento], [idPermissaoTipo], [idMenu]) VALUES (1, 1, 3)
INSERT [dbo].[Permissoes] ([idDepartamento], [idPermissaoTipo], [idMenu]) VALUES (1, 1, 4)
/****** Object:  Table [dbo].[MenuPermissoes]    Script Date: 06/16/2014 20:28:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MenuPermissoes](
	[idMenu] [int] NOT NULL,
	[idPermissaoTipo] [int] NOT NULL
) ON [PRIMARY]
GO
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (1, 1)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (1, 2)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (1, 3)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (1, 4)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (2, 1)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (2, 2)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (2, 3)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (2, 4)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (3, 1)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (3, 2)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (3, 3)
INSERT [dbo].[MenuPermissoes] ([idMenu], [idPermissaoTipo]) VALUES (3, 4)
/****** Object:  Default [DF_Menu_idMenuPai]    Script Date: 06/16/2014 20:28:50 ******/
ALTER TABLE [dbo].[Menu] ADD  CONSTRAINT [DF_Menu_idMenuPai]  DEFAULT ((0)) FOR [idMenuPai]
GO
/****** Object:  ForeignKey [FK_MenuPermissoes_Menu]    Script Date: 06/16/2014 20:28:50 ******/
ALTER TABLE [dbo].[MenuPermissoes]  WITH CHECK ADD  CONSTRAINT [FK_MenuPermissoes_Menu] FOREIGN KEY([idMenu])
REFERENCES [dbo].[Menu] ([idMenu])
GO
ALTER TABLE [dbo].[MenuPermissoes] CHECK CONSTRAINT [FK_MenuPermissoes_Menu]
GO
/****** Object:  ForeignKey [FK_MenuPermissoes_PermissoesTipos]    Script Date: 06/16/2014 20:28:50 ******/
ALTER TABLE [dbo].[MenuPermissoes]  WITH CHECK ADD  CONSTRAINT [FK_MenuPermissoes_PermissoesTipos] FOREIGN KEY([idPermissaoTipo])
REFERENCES [dbo].[PermissoesTipos] ([idPermissaoTipo])
GO
ALTER TABLE [dbo].[MenuPermissoes] CHECK CONSTRAINT [FK_MenuPermissoes_PermissoesTipos]
GO
/****** Object:  ForeignKey [FK_Permissoes_Departamentos]    Script Date: 06/16/2014 20:28:50 ******/
ALTER TABLE [dbo].[Permissoes]  WITH CHECK ADD  CONSTRAINT [FK_Permissoes_Departamentos] FOREIGN KEY([idDepartamento])
REFERENCES [dbo].[Departamentos] ([idDepartamento])
GO
ALTER TABLE [dbo].[Permissoes] CHECK CONSTRAINT [FK_Permissoes_Departamentos]
GO
/****** Object:  ForeignKey [FK_Permissoes_Menu]    Script Date: 06/16/2014 20:28:50 ******/
ALTER TABLE [dbo].[Permissoes]  WITH CHECK ADD  CONSTRAINT [FK_Permissoes_Menu] FOREIGN KEY([idMenu])
REFERENCES [dbo].[Menu] ([idMenu])
GO
ALTER TABLE [dbo].[Permissoes] CHECK CONSTRAINT [FK_Permissoes_Menu]
GO
/****** Object:  ForeignKey [FK_Permissoes_PermissoesTipos]    Script Date: 06/16/2014 20:28:50 ******/
ALTER TABLE [dbo].[Permissoes]  WITH CHECK ADD  CONSTRAINT [FK_Permissoes_PermissoesTipos] FOREIGN KEY([idPermissaoTipo])
REFERENCES [dbo].[PermissoesTipos] ([idPermissaoTipo])
GO
ALTER TABLE [dbo].[Permissoes] CHECK CONSTRAINT [FK_Permissoes_PermissoesTipos]
GO
/****** Object:  ForeignKey [FK_Usuarios_Empresas]    Script Date: 06/16/2014 20:28:50 ******/
ALTER TABLE [dbo].[Usuarios]  WITH CHECK ADD  CONSTRAINT [FK_Usuarios_Empresas] FOREIGN KEY([idEmpresa])
REFERENCES [dbo].[Empresas] ([idEmpresa])
GO
ALTER TABLE [dbo].[Usuarios] CHECK CONSTRAINT [FK_Usuarios_Empresas]
GO
/****** Object:  ForeignKey [FK_Usuarios_Usuarios]    Script Date: 06/16/2014 20:28:50 ******/
ALTER TABLE [dbo].[Usuarios]  WITH CHECK ADD  CONSTRAINT [FK_Usuarios_Usuarios] FOREIGN KEY([idDepartamento])
REFERENCES [dbo].[Departamentos] ([idDepartamento])
GO
ALTER TABLE [dbo].[Usuarios] CHECK CONSTRAINT [FK_Usuarios_Usuarios]
GO
