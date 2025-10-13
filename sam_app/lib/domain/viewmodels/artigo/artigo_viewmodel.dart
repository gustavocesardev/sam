import 'package:flutter/material.dart';
import 'package:flutter_quill/flutter_quill.dart' as quill;
import 'package:sam_app/data/repositories/artigo/artigo_repository.dart';
import 'package:sam_app/data/models/artigo_model.dart';
import 'package:sam_app/data/services/artigo/artigo_service.dart';

class ArtigoViewmodel extends ChangeNotifier {
  final int idArtigo;
  ArtigoViewmodel({required this.idArtigo});

  final ArtigoService service = ArtigoService();
  ArtigoModel? artigo;
  quill.QuillController? conteudoController;

  bool isLoading = false;
  bool isDownloadingPdf = false;

  Future<void> loadArtigo() async {
    isLoading = true;
    notifyListeners();

    final repository = ArtigoRepository();
    final a = await repository.index(idArtigo);

    artigo = a;
    conteudoController = quill.QuillController(
      document: quill.Document.fromJson(a.conteudo),
      selection: const TextSelection.collapsed(offset: 0),
    );
    conteudoController?.formatText(
      0,
      conteudoController!.document.length,
      quill.Attribute.justifyAlignment,
    );

    isLoading = false;
    notifyListeners();
  }

  Future<void> downloadPdfWithLoading() async {
    if (artigo?.pdf == null) return;

    isDownloadingPdf = true;
    notifyListeners();
    try {

      await service.downloadPdf(artigo!.pdf!, artigo!.titulo);
      
    } finally {
      isDownloadingPdf = false;
      notifyListeners();
    }
  }
}
