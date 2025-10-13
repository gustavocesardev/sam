import 'dart:io';
import 'dart:typed_data';
import 'package:crop_your_image/crop_your_image.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:path_provider/path_provider.dart';
import 'package:sam_app/data/models/user_detail_model.dart';
import 'package:sam_app/data/repositories/user_repository.dart';
import 'package:sam_app/data/services/http_service.dart';
import 'package:sam_app/shared/constants.dart';

String imageUrlFromHash(String hash) => '$baseUrl/file/image/$hash';

class UserEditViewmodel extends ChangeNotifier {
  final UserRepository _repository = UserRepository();

  bool isLoading = false;
  bool isLoadingUpdate = false;
  bool showCropper = false;
  bool isCropping = false;

  UserDetailModel? user;

  final TextEditingController nameController = TextEditingController();
  final TextEditingController bioController = TextEditingController();

  final CropController cropController = CropController();
  final ImagePicker _picker = ImagePicker();

  File? avatarFile;
  Uint8List? avatarImageData;
  Uint8List? imageToCrop;
  bool isCroppingAvatar = true;
  String? avatarHash;

  Future<void> loadUser(int id) async {
    isLoading = true;
    if (mounted) notifyListeners();

    try {
      user = await _repository.getUserDetails(id);
      nameController.text = user?.nome ?? '';
      bioController.text = user?.biografia ?? '';

      avatarHash = user?.avatarEncrypted;

      if (avatarHash != null) {
        try {
          final httpService = HttpService();
          final avatarBytes = await httpService.fetchImageWithRetry(
            imageUrlFromHash(avatarHash!),
          );
          avatarImageData = avatarBytes;
          avatarFile = await _uint8ListToFile(avatarBytes, 'avatar_loaded.png');
        } catch (e) {
          debugPrint('Erro ao carregar avatar: $e');
        }
      }
    } finally {
      if (mounted) {
        isLoading = false;
        notifyListeners();
      }
    }
  }

  Future<File> _uint8ListToFile(Uint8List data, String filename) async {
    final dir = await getTemporaryDirectory();
    final file = File('${dir.path}/$filename');
    await file.writeAsBytes(data);
    return file;
  }

  Future<void> pickImageAsAvatar() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
    if (pickedFile == null) return;

    final bytes = await pickedFile.readAsBytes();
    imageToCrop = bytes;
    isCroppingAvatar = true;
    showCropper = true;
    notifyListeners();
  }

  void onCropped(CropResult result) async {
    isCropping = true;
    if (result is CropSuccess) {
      avatarImageData = result.croppedImage;
      avatarFile = await _uint8ListToFile(result.croppedImage, 'avatar.png');
      showCropper = false;
      isCropping = false;
      imageToCrop = null;
      notifyListeners();
    } else if (result is CropFailure) {}
  }

  void onCropCancel() {
    showCropper = false;
    imageToCrop = null;
    notifyListeners();
  }

  void clearAvatarImage() {
    avatarImageData = null;
    avatarFile = null;
    avatarHash = null;
    notifyListeners();
  }

  Future<void> updateUser(int id) async {
    if (user == null) return;

    isLoadingUpdate = true;
    notifyListeners();

    try {
      await _repository.updateUser(
        id: id,
        name: nameController.text,
        biografia: bioController.text,
        image: avatarFile,
        anoInicio: user!.anoInicioCurso,
        anoFim: user!.anoFimCurso,
      );
    } finally {
      isLoadingUpdate = false;
      notifyListeners();
    }
  }

  bool _disposed = false;

  @override
  void dispose() {
    _disposed = true;
    nameController.dispose();
    bioController.dispose();
    super.dispose();
  }
  bool get mounted => !_disposed;
}
