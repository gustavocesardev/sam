import 'dart:io';

class GrupoEstudoRequest {
  final int? idGrupoEstudo;
  final int idCurso;
  final int idUsuario;
  final String nomeGrupo;
  final String descricao;
  final String hashtags;
  final File imagem;
  final File imagemHeader;

  GrupoEstudoRequest({
    this.idGrupoEstudo,
    required this.idCurso,
    required this.idUsuario,
    required this.nomeGrupo,
    required this.descricao,
    required this.hashtags,
    required this.imagem,
    required this.imagemHeader
  });
}
