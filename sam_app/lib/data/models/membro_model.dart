class MembroModel {
  final int idMembro;
  final int idUsuario;
  final String nome;
  final String? fotoPerfilHash;
  final String curso;
  final int idGrupoEstudo;
  final String situacao;

  MembroModel({
    required this.idMembro,
    required this.idUsuario,
    required this.nome,
    required this.curso,
    required this.idGrupoEstudo,
    required this.situacao,
    this.fotoPerfilHash,
  });

  factory MembroModel.fromJson(Map<String, dynamic> json) {
    return MembroModel(
      idMembro: json['id_membro'] as int,
      idUsuario: json['id_usuario'] as int,
      nome: json['nome'] ?? '',
      curso: json['curso'] ?? '',
      idGrupoEstudo: json['id_grupo_estudo'] as int,
      situacao: json['situacao'] ?? '',
      fotoPerfilHash: json['foto_perfil'],
    );
  }
}