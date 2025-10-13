import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/data/models/user_model.dart';
import 'package:sam_app/data/repositories/publicacao/publicacao_repository.dart';
import 'package:sam_app/data/requests/publicacao_request.dart';
import 'package:sam_app/data/services/publicacao/publicacao_service.dart';
import 'package:sam_app/data/services/user_service.dart';
import 'package:sam_app/data/storage/auth_storage_service.dart';
import 'package:sam_app/shared/constants.dart';

class PostCreateViewmodel extends ChangeNotifier {
  final int idAutor;
  final TipoAutorPublicacao tipoAutor;
  final int? idPublicacaoVinculada;

  final TextEditingController textController = TextEditingController();
  final List<XFile> images = [];
  final ImagePicker _picker = ImagePicker();
  final UserService _userService = UserService();
  final PublicacaoService _publicacaoService = PublicacaoService();

  bool isLoading = false;

  bool _isPickingImage = false;

  String? userImageUrl;
  String? instituicaoImageUrl;

  PostCreateViewmodel({
    required this.idAutor,
    required this.tipoAutor,
    required this.idPublicacaoVinculada,
  }) {
    _loadUser();
    textController.addListener(() {
      notifyListeners();
    });
  }

  bool get canPublish =>
      textController.text.trim().isNotEmpty || images.isNotEmpty;
  bool get isPickingImage => _isPickingImage;

  Future<void> pickImage(BuildContext context) async {
    if (_isPickingImage) return;

    if (images.length >= 4) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: const Text(
            'Você só pode adicionar até 4 imagens.',
            style: TextStyle(color: Colors.white),
            textAlign: TextAlign.center,
          ),
          behavior: SnackBarBehavior.floating,
          backgroundColor: Colors.red[700],
        ),
      );
      return;
    }

    _isPickingImage = true;
    notifyListeners();

    try {
      final XFile? picked = await _picker.pickImage(
        source: ImageSource.gallery,
      );

      if (picked != null && images.length < 4) {
        images.add(picked);
        notifyListeners();
      }
    } catch (e) {
      debugPrint('Erro ao selecionar imagem: $e');
    } finally {
      _isPickingImage = false;
      notifyListeners();
    }
  }

  void removeImage(int index) {
    images.removeAt(index);
    notifyListeners();
  }

  Future<void> publish(BuildContext context) async {
    if (!canPublish) return;

    if (isLoading) return;
    isLoading = true;
    notifyListeners();

    final repository = PublicacaoRepository(_publicacaoService);

    try {
      FocusScope.of(context).unfocus();

      final request = PublicacaoRequest(
        idAutor: idAutor,
        texto: textController.text.trim(),
        idPublicacaoVinculada: idPublicacaoVinculada,
        imagens: images.map((x) => File(x.path)).toList(),
      );

      await repository.criarPublicacao(
        chaveAutor: tipoAutor.atributo,
        request: request,
      );

      isLoading = false;
      textController.clear();
      images.clear();
      notifyListeners();
    } catch (e) {
      rethrow;
    }
  }

  Future<void> _loadUser() async {
    final user = await AuthStorageService.getStoredUser();
    if (user != null) {
      final UserModel? currentUser = await _userService.getUser(user.id);
      userImageUrl = "$baseUrl/file/image/${currentUser?.avatarEncrypted}";
      notifyListeners();
    }
  }

  @override
  void dispose() {
    textController.dispose();
    super.dispose();
  }
}
