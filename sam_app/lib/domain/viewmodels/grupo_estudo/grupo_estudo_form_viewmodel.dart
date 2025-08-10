import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:crop_your_image/crop_your_image.dart';

class GrupoEstudoFormViewModel extends ChangeNotifier {
  final int? idGrupoEstudo;

  GrupoEstudoFormViewModel({this.idGrupoEstudo});

  final TextEditingController nomeController = TextEditingController();
  final TextEditingController descricaoController = TextEditingController();
  final TextEditingController hashtagsController = TextEditingController();

  final ImagePicker _picker = ImagePicker();
  final CropController cropController = CropController();

  Uint8List? avatarImageData;
  Uint8List? headerImageData;

  Uint8List? imageToCrop;
  bool isCroppingAvatar = false;
  bool showCropper = false;

  bool isLoading = false;

  Future<void> init() async {
    if (idGrupoEstudo != null) {
      await carregarFormulario(idGrupoEstudo!);
    }
  }

  Future<void> carregarFormulario(int id) async {
    isLoading = true;
    notifyListeners();

    // TODO: carregar dados do service/repository e preencher controllers e imagens

    isLoading = false;
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

  void onCropped(CropResult result) {
    if (result is CropSuccess) {
      if (isCroppingAvatar) {
        avatarImageData = result.croppedImage;
      } else {
        headerImageData = result.croppedImage;
      }
      showCropper = false;
      imageToCrop = null;
      notifyListeners();
    } else if (result is CropFailure) {

    }
  }

  void onCropCancel() {
    showCropper = false;
    imageToCrop = null;
    notifyListeners();
  }

  void clearAvatarImage() {
    avatarImageData = null;
    notifyListeners();
  }

  void clearHeaderImage() {
    headerImageData = null;
    notifyListeners();
  }

  void salvarGrupo() {
    // TODO: chamar service/repository para salvar ou atualizar
  }

  @override
  void dispose() {
    nomeController.dispose();
    descricaoController.dispose();
    hashtagsController.dispose();
    super.dispose();
  }
}