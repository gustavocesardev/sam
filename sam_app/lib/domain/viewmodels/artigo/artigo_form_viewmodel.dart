import 'dart:io';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:flutter_quill/flutter_quill.dart' as quill;
import 'package:sam_app/data/repositories/artigo/artigo_repository.dart';
import 'package:sam_app/data/requests/artigo_request.dart';
import 'package:sam_app/data/services/artigo/artigo_service.dart';

class ArtigoFormViewModel extends ChangeNotifier {
  final int idUsuario;
  final int? idArtigo;

  final ArtigoService service = ArtigoService();

  ArtigoFormViewModel({required this.idUsuario, this.idArtigo});

  final TextEditingController tituloController = TextEditingController();
  final TextEditingController hashtagsController = TextEditingController();
  final quill.QuillController conteudoController =
      quill.QuillController.basic();

  File? pdfSelecionado;
  String? pdfUrl;

  bool isLoadingData = false;
  bool isLoadingDelete = false;
  bool isSaving = false;

  void setLoadingData(bool value) {
    isLoadingData = value;
    notifyListeners();
  }

  void setLoadingDelete(bool value) {
    isLoadingDelete = value;
    notifyListeners();
  }

  void setSaving(bool value) {
    isSaving = value;
    notifyListeners();
  }

  Future<void> init() async {
    if (idArtigo != null) {
      await carregarArtigo(idArtigo!);
    }
  }

  Future<void> carregarArtigo(int id) async {
    setLoadingData(true);

    final repository = ArtigoRepository();
    final artigo = await repository.index(id);

    tituloController.text = artigo.titulo;
    hashtagsController.text = artigo.palavrasChave;

    conteudoController.document = quill.Document.fromJson(artigo.conteudo);

    pdfUrl = artigo.pdf;

    pdfSelecionado = null;

    setLoadingData(false);
  }

  Future<void> selecionarPDF() async {
    final result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['pdf'],
    );
    if (result != null && result.files.single.path != null) {
      pdfSelecionado = File(result.files.single.path!);
      notifyListeners();
    }
  }

  Future<void> salvarArtigo() async {
    setSaving(true);

    try {

      final repository = ArtigoRepository();

      final ArtigoRequest request = ArtigoRequest(
        idUsuario: idUsuario,
        idArtigo: idArtigo,
        titulo: tituloController.text.trim(),
        hashtags: hashtagsController.text.trim(),
        conteudo: conteudoController.document.toDelta().toJson(),
        pdfFile: pdfSelecionado,
      );

      await repository.store(request: request);

    } catch (e) {
      rethrow;
    }
  }

  Future<void> excluirArtigo() async {
    if (idArtigo == null) return;

    setLoadingDelete(true);
    try {

      final repository = ArtigoRepository();
      await repository.delete(id: idArtigo!);
      
    } finally {
      setLoadingDelete(false);
    }
  }

  @override
  void dispose() {
    tituloController.dispose();
    hashtagsController.dispose();
    conteudoController.dispose();
    super.dispose();
  }
}
