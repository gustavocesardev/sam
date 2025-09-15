import 'dart:io';

class ArtigoRequest {
  final int? idArtigo;
  final int idUsuario;
  final String titulo;
  final String hashtags;
  final List<dynamic> conteudo;
  final File? pdfFile;

  ArtigoRequest({
    this.idArtigo,
    required this.idUsuario,
    required this.titulo,
    required this.hashtags,
    required this.conteudo,
    this.pdfFile,
  });
}
