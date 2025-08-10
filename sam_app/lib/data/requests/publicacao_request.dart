import 'dart:io';

class PublicacaoRequest {
  final int idAutor;
  final String texto;
  final int? idPublicacaoVinculada;
  final List<File>? imagens;

  PublicacaoRequest({
    required this.idAutor,
    required this.texto,
    required this.idPublicacaoVinculada,
    required this.imagens
  });
}
