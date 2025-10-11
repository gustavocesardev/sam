import 'package:crop_your_image/crop_your_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/domain/viewmodels/user_edit_viewmodel.dart';
import 'package:sam_app/presentation/widgets/buttons/custom_icon_button.dart';
import 'package:sam_app/presentation/widgets/buttons/loading_button.dart';
import 'package:sam_app/presentation/widgets/input/custom_text_form_field.dart';
import 'package:sam_app/presentation/widgets/snack/top_snack_bar.dart';

class UserEditPage extends StatefulWidget {
  final int userId;

  const UserEditPage({super.key, required this.userId});

  @override
  State<UserEditPage> createState() => _UserEditPageState();
}

class _UserEditPageState extends State<UserEditPage> {
  final _userKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    Future.microtask(() =>
        Provider.of<UserEditViewmodel>(context, listen: false)
            .loadUser(widget.userId));
  }

  @override
  Widget build(BuildContext context) {
    final vm = Provider.of<UserEditViewmodel>(context);

    return Scaffold(
      appBar: AppBar(title: const Text('Editar perfil')),
      body: Stack(
        children: [
          vm.isLoading
              ? const Center(child: CircularProgressIndicator())
              : SingleChildScrollView(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 28, vertical: 20),
                  child: Form(
                    key: _userKey,
                    child: Column(
                      children: [
                        Stack(
                          alignment: Alignment.center,
                          children: [
                            GestureDetector(
                              onTap: vm.pickImageAsAvatar,
                              child: Container(
                                height: 200,
                                width: 200,
                                decoration: BoxDecoration(
                                  color: Theme.of(context).colorScheme.primary,
                                  shape: BoxShape.circle,
                                  image: vm.avatarImageData != null
                                      ? DecorationImage(
                                          image: MemoryImage(vm.avatarImageData!),
                                          fit: BoxFit.cover,
                                        )
                                      : null,
                                ),
                                child: Stack(
                                  alignment: Alignment.center,
                                  children: [
                                    if (vm.avatarImageData != null)
                                      Container(
                                        decoration: const BoxDecoration(
                                          shape: BoxShape.circle,
                                          color: Colors.black26,
                                        ),
                                      ),
                                    const Icon(
                                      Icons.camera_alt_outlined,
                                      color: Colors.white70,
                                      size: 60,
                                    ),
                                  ],
                                ),
                              ),
                            ),

                            if (vm.avatarImageData != null)
                              Positioned(
                                top: 1,
                                right: 1,
                                child: GestureDetector(
                                  onTap: vm.clearAvatarImage,
                                  child: Container(
                                    decoration: BoxDecoration(
                                      color: Theme.of(context)
                                          .scaffoldBackgroundColor,
                                      shape: BoxShape.circle,
                                      border: Border.all(width: 0.5),
                                    ),
                                    child: const Icon(
                                      Icons.close,
                                      color: Colors.white,
                                      size: 24,
                                    ),
                                  ),
                                ),
                              ),
                          ],
                        ),
                        const SizedBox(height: 60),
                        CustomTextFormField(
                          label: 'Nome completo',
                          hint: 'Informe o nome completo',
                          controller: vm.nameController,
                        ),
                        const SizedBox(height: 16),
                        CustomTextFormField(
                          label: 'Biografia',
                          hint: 'Informe a biografia',
                          maxLength: 250,
                          maxLines: 10,
                          controller: vm.bioController,
                        ),
                        const SizedBox(height: 24),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            CustomIconButton(
                              label: "Cancelar",
                              color: Colors.red.shade700,
                              icon: Icons.close,
                              onPressed: () async => Navigator.pop(context),
                            ),
                            vm.isLoadingUpdate
                                ? const LoadingButtonSimple()
                                : CustomIconButton(
                                    label: "Finalizar",
                                    color:
                                        Theme.of(context).colorScheme.primary,
                                    icon: Icons.arrow_forward,
                                    onPressed: () async {
                                      if (_userKey.currentState!.validate()) {
                                        try {
                                          if (vm.avatarImageData == null) {
                                            TopSnackBar.show(
                                              context,
                                              'A imagem do perfil é obrigatória!',
                                              color: Colors.red[700],
                                            );
                                            return;
                                          }

                                          await vm.updateUser(widget.userId);
                                          if (context.mounted) {
                                            TopSnackBar.show(
                                              context,
                                              'Perfil alterado com sucesso!',
                                            );
                                            Navigator.pop(context, true);
                                          }
                                        } catch (error) {
                                          if (context.mounted) {
                                            TopSnackBar.show(
                                              context,
                                              error.toString(),
                                              color: Colors.red[700],
                                            );
                                          }
                                        }
                                      }
                                    },
                                  ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),

          // CROP
          if (vm.showCropper && vm.imageToCrop != null)
            Container(
              color: Colors.black,
              alignment: Alignment.center,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  SizedBox(
                    width: 350,
                    height: 550,
                    child: Crop(
                      image: vm.imageToCrop!,
                      controller: vm.cropController,
                      withCircleUi: true,
                      aspectRatio: 1,
                      onCropped: vm.onCropped,
                      baseColor: Colors.black,
                      maskColor: Colors.black54,
                    ),
                  ),
                  const SizedBox(height: 28),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      ElevatedButton(
                        onPressed: vm.onCropCancel,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.grey[800],
                          padding: const EdgeInsets.symmetric(
                            horizontal: 25,
                            vertical: 15,
                          ),
                        ),
                        child: const Text('Cancelar'),
                      ),
                      const SizedBox(width: 20),
                      ElevatedButton(
                        onPressed: vm.cropController.crop,
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 25,
                            vertical: 15,
                          ),
                        ),
                        child: const Text('Confirmar'),
                      ),
                    ],
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }
}
