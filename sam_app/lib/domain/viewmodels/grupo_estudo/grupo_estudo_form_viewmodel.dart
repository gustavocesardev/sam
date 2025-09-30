import 'dart:io';
import 'dart:typed_data';
import 'package:crop_your_image/crop_your_image.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:path_provider/path_provider.dart';
import 'package:sam_app/data/repositories/grupo_estudo/grupos_estudo_repository.dart';
import 'package:sam_app/data/requests/grupo_estudo_request.dart';
import 'package:sam_app/data/services/http_service.dart';
import 'package:sam_app/shared/constants.dart';
import 'package:sam_app/shared/utils/storage_utils.dart';

String imageUrlFromHash(String hash) => '$baseUrl/file/image/$hash';

class GrupoEstudoFormViewModel extends ChangeNotifier {
  final int idUsuario;
  final int? idGrupoEstudo;

  final GruposEstudoRepository repository = GruposEstudoRepository();

  GrupoEstudoFormViewModel({this.idGrupoEstudo, required this.idUsuario});

  final TextEditingController nomeController = TextEditingController();
  final TextEditingController descricaoController = TextEditingController();
  final TextEditingController hashtagsController = TextEditingController();

  final CropController cropController = CropController();
  final ImagePicker _picker = ImagePicker();

  File? avatarFile;
  File? headerFile;

  String? avatarHash;
  String? headerHash;

  Uint8List? avatarImageData;
  Uint8List? headerImageData;

  Uint8List? imageToCrop;
  bool isCroppingAvatar = false;
  bool showCropper = false;

  bool isLoading = false;
  bool isSaving = false;
  bool isDeleting = false;

  int? idCurso;

  Future<void> init() async {
    isLoading = true;
    notifyListeners();

    try {

      if (idGrupoEstudo != null) {
        await carregarGrupoEstudo(idGrupoEstudo!);
      }

      idCurso = await StorageUtils.getIdCurso();

    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  Future<void> carregarGrupoEstudo(int id) async {
    final grupo = await repository.index(id: id);

    nomeController.text = grupo.nomeGrupo;
    descricaoController.text = grupo.descricao;
    hashtagsController.text = grupo.hashtags;

    avatarHash = grupo.imagem;
    headerHash = grupo.imagemHeader;

    final httpService = HttpService();

    if (avatarHash != null) {
      try {

        final avatarBytes = await httpService.fetchImageWithRetry(
          imageUrlFromHash(avatarHash!),
        );
        avatarImageData = avatarBytes;
        avatarFile = await _uint8ListToFile(avatarBytes, 'avatar_loaded.png');

      } catch (e) {
        debugPrint('Erro ao carregar avatar: $e');
      }
    }

    if (headerHash != null) {
      try {

        final headerBytes = await httpService.fetchImageWithRetry(
          imageUrlFromHash(headerHash!),
        );
        headerImageData = headerBytes;
        headerFile = await _uint8ListToFile(headerBytes, 'header_loaded.png');

      } catch (e) {
        debugPrint('Erro ao carregar header: $e');
      }
    }

    notifyListeners();
  }

  Future<void> salvarGrupo() async {
    if (avatarFile == null) {
      throw Exception('Selecione uma imagem de avatar');
    }
    if (headerFile == null) {
      throw Exception('Selecione uma imagem de header');
    }

    isSaving = true;
    notifyListeners();

    try {

      final request = GrupoEstudoRequest(
        idGrupoEstudo: idGrupoEstudo,
        idCurso: idCurso!,
        idUsuario: idUsuario,
        nomeGrupo: nomeController.text.trim(),
        descricao: descricaoController.text.trim(),
        hashtags: hashtagsController.text.trim(),
        imagem: avatarFile!,
        imagemHeader: headerFile!,
      );

      await repository.store(request: request);
      
    } finally {
      isSaving = false;
      notifyListeners();
    }
  }

  Future<void> excluirGrupo() async {
    if (idGrupoEstudo == null) return;
    isDeleting = true;
    notifyListeners();

    try {
      await repository.delete(id: idGrupoEstudo!);
    } finally {
      isDeleting = false;
      notifyListeners();
    }
  }

  Future<void> pickAvatar() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      avatarFile = File(pickedFile.path);
      notifyListeners();
    }
  }

  Future<void> pickHeader() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      headerFile = File(pickedFile.path);
      notifyListeners();
    }
  }

  void clearAvatar() {
    avatarFile = null;
    avatarHash = null;
    notifyListeners();
  }

  void clearHeader() {
    headerFile = null;
    headerHash = null;
    notifyListeners();
  }

  Future<void> pickImageAsAvatar() async {
    await _pickImage(isAvatar: true);
  }

  Future<void> pickImageAsHeader() async {
    await _pickImage(isAvatar: false);
  }

  Future<void> _pickImage({required bool isAvatar}) async {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
    if (pickedFile == null) return;
    final bytes = await pickedFile.readAsBytes();

    imageToCrop = bytes;
    isCroppingAvatar = isAvatar;
    showCropper = true;
    notifyListeners();
  }

  void onCropped(CropResult result) async {
    if (result is CropSuccess) {
      if (isCroppingAvatar) {
        avatarImageData = result.croppedImage;
        avatarFile = await _uint8ListToFile(result.croppedImage, 'avatar.png');
      } else {
        headerImageData = result.croppedImage;
        headerFile = await _uint8ListToFile(result.croppedImage, 'header.png');
      }

      showCropper = false;
      imageToCrop = null;
      notifyListeners();
    } else if (result is CropFailure) {}
  }

  Future<File> _uint8ListToFile(Uint8List data, String filename) async {
    final dir = await getTemporaryDirectory();
    final file = File('${dir.path}/$filename');
    await file.writeAsBytes(data);
    return file;
  }

  void onCropCancel() {
    showCropper = false;
    imageToCrop = null;
    notifyListeners();
  }

  void clearAvatarImage() {
    avatarImageData = null;
    avatarFile = null;
    notifyListeners();
  }

  void clearHeaderImage() {
    headerImageData = null;
    headerFile = null;
    notifyListeners();
  }

  @override
  void dispose() {
    nomeController.dispose();
    descricaoController.dispose();
    hashtagsController.dispose();
    super.dispose();
  }
}
