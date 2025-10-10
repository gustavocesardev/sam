class UserDetailModel {
  final int id;
  final int idCurso;
  final String nome;
  final String email;
  final String? biografia;
  final String situacao;
  final int anoInicioCurso;
  final int anoFimCurso;
  final String? avatarEncrypted;
  final String criadoEm;
  final String atualizadoEm;
  final String nomeCurso;
  final int totalArtigos;
  final int totalPublicacoes;
  final int totalComentarios;

  UserDetailModel({
    required this.id,
    required this.idCurso,
    required this.nome,
    required this.email,
    required this.biografia,
    required this.situacao,
    required this.anoInicioCurso,
    required this.anoFimCurso,
    this.avatarEncrypted,
    required this.criadoEm,
    required this.atualizadoEm,
    required this.nomeCurso,
    required this.totalArtigos,
    required this.totalPublicacoes,
    required this.totalComentarios
  });

  factory UserDetailModel.fromJson(Map<String, dynamic> json) {
    final curso = json['curso']!;
    final contadores = json['contadores']!;

    return UserDetailModel(
      id: json['id'],
      idCurso: json['id_curso'],
      nome: json['name'],
      email: json['email'],
      biografia: json['biografia'],
      situacao: json['situacao'],
      anoInicioCurso: json['ano_inicio_curso'],
      anoFimCurso: json['ano_fim_curso'],
      avatarEncrypted: json['foto_perfil'],
      criadoEm: json['created_at'],
      atualizadoEm: json['updated_at'],
      nomeCurso: curso['nome'],
      totalArtigos: contadores['artigos'] ?? 0,
      totalPublicacoes: contadores['publicacoes'] ?? 0,
      totalComentarios: contadores['comentarios'] ?? 0,
    );
  }
}