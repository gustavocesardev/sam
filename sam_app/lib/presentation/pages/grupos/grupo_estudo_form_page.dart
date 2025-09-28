import 'package:flutter/material.dart';
import 'package:crop_your_image/crop_your_image.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupo_estudo_form_viewmodel.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';
import 'package:sam_app/presentation/widgets/buttons/custom_icon_button.dart';
import 'package:sam_app/presentation/widgets/buttons/loading_button.dart';
import 'package:sam_app/presentation/widgets/input/custom_text_form_field.dart';
import 'package:sam_app/presentation/widgets/snack/top_snack_bar.dart';

class GrupoEstudoFormPage extends StatefulWidget {
  final int idUsuario;
  final int? idGrupoEstudo;

  const GrupoEstudoFormPage({
    super.key,
    required this.idUsuario,
    this.idGrupoEstudo,
  });

  @override
  State<GrupoEstudoFormPage> createState() => _GrupoEstudoFormPageState();
}

class _GrupoEstudoFormPageState extends State<GrupoEstudoFormPage> {
  late GrupoEstudoFormViewModel vm;
  final _formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    vm = GrupoEstudoFormViewModel(
      idGrupoEstudo: widget.idGrupoEstudo,
      idUsuario: widget.idUsuario,
    );
    vm.addListener(() {
      if (mounted) setState(() {});
    });
    vm.init();
  }

  @override
  void dispose() {
    vm.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      appBar: SimpleAppBar(
        textAppBar: widget.idGrupoEstudo == null
            ? 'Novo grupo de estudo'
            : 'Editar grupo de estudo',
      ),
      body: vm.isLoading
          ? const Center(child: CircularProgressIndicator())
          : Stack(
              children: [
                SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 28,
                    vertical: 20,
                  ),
                  child: Form(
                    key: _formKey,
                    child: Column(
                      children: [
                        // Header + Avatar
                        SizedBox(
                          height: 140,
                          child: Stack(
                            clipBehavior: Clip.none,
                            children: [
                              // HEADER
                              GestureDetector(
                                onTap: vm.pickImageAsHeader,
                                child: Container(
                                  height: 120,
                                  width: double.infinity,
                                  decoration: BoxDecoration(
                                    color: Theme.of(
                                      context,
                                    ).colorScheme.primary,
                                    borderRadius: BorderRadius.circular(12),
                                    image: vm.headerImageData != null
                                        ? DecorationImage(
                                            image: MemoryImage(
                                              vm.headerImageData!,
                                            ),
                                            fit: BoxFit.cover,
                                          )
                                        : null,
                                  ),
                                  child: Stack(
                                    children: [
                                      Container(
                                        decoration: BoxDecoration(
                                          borderRadius: BorderRadius.circular(
                                            12,
                                          ),
                                          color: Colors.black26,
                                        ),
                                      ),
                                      const Center(
                                        child: Icon(
                                          Icons.camera_alt_outlined,
                                          color: Colors.white70,
                                          size: 30,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ),

                              if (vm.headerImageData != null)
                                Positioned(
                                  top: 8,
                                  right: 8,
                                  child: GestureDetector(
                                    onTap: vm.clearHeaderImage,
                                    child: Container(
                                      decoration: BoxDecoration(
                                        color: Theme.of(
                                          context,
                                        ).scaffoldBackgroundColor,
                                        shape: BoxShape.circle,
                                        border: Border.all(width: 0.5),
                                      ),
                                      child: const Icon(
                                        Icons.close,
                                        color: Colors.white,
                                        size: 20,
                                      ),
                                    ),
                                  ),
                                ),

                              // AVATAR
                              Positioned(
                                top: 60,
                                left: 16,
                                child: GestureDetector(
                                  onTap: vm.pickImageAsAvatar,
                                  child: Stack(
                                    clipBehavior: Clip.none,
                                    children: [
                                      CircleAvatar(
                                        radius: 60,
                                        backgroundColor: Theme.of(
                                          context,
                                        ).colorScheme.primary,
                                        backgroundImage:
                                            vm.avatarImageData != null
                                            ? MemoryImage(vm.avatarImageData!)
                                            : null,
                                      ),
                                      Container(
                                        width: 120,
                                        height: 120,
                                        decoration: BoxDecoration(
                                          shape: BoxShape.circle,
                                          color: Colors.black26,
                                          border: Border.all(
                                            color: Theme.of(
                                              context,
                                            ).scaffoldBackgroundColor,
                                            width: 2,
                                          ),
                                        ),
                                        child: const Center(
                                          child: Icon(
                                            Icons.camera_alt_outlined,
                                            color: Colors.white70,
                                            size: 30,
                                          ),
                                        ),
                                      ),

                                      if (vm.avatarImageData != null)
                                        Positioned(
                                          top: -8,
                                          right: -8,
                                          child: GestureDetector(
                                            onTap: vm.clearAvatarImage,
                                            child: Container(
                                              decoration: BoxDecoration(
                                                color: Theme.of(
                                                  context,
                                                ).scaffoldBackgroundColor,
                                                shape: BoxShape.circle,
                                                border: Border.all(width: 0.5),
                                              ),
                                              child: const Icon(
                                                Icons.close,
                                                color: Colors.white,
                                                size: 20,
                                              ),
                                            ),
                                          ),
                                        ),
                                    ],
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),

                        const SizedBox(height: 62),
                        CustomTextFormField(
                          controller: vm.nomeController,
                          label: 'Nome*',
                          hint: 'Informe o nome',
                        ),
                        const SizedBox(height: 20),
                        CustomTextFormField(
                          controller: vm.descricaoController,
                          label: 'Descrição*',
                          hint: 'Informe a descrição',
                          maxLines: 10,
                        ),
                        const SizedBox(height: 20),
                        CustomTextFormField(
                          controller: vm.hashtagsController,
                          label: 'Hashtags*',
                          hint: 'Ex: #dev #adm',
                        ),

                        const SizedBox(height: 48),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            vm.idGrupoEstudo != null
                                ? vm.isDeleting
                                      ? const LoadingButtonSimple()
                                      : CustomIconButton(
                                          label: "Excluir",
                                          color: Colors.red.shade700,
                                          icon: Icons.close,
                                          onPressed: () async {
                                            try {
                                              await vm.excluirGrupo();
                                              if (context.mounted) {
                                                TopSnackBar.show(
                                                  context,
                                                  'Grupo de estudo excluído com sucesso!',
                                                  color: Colors.orange[800],
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
                                          },
                                        )
                                : CustomIconButton(
                                    label: "Cancelar",
                                    color: Colors.red.shade700,
                                    icon: Icons.close,
                                    onPressed: () async =>
                                        Navigator.pop(context),
                                  ),
                            vm.isLoading
                                ? const LoadingButtonSimple()
                                : ElevatedButton.icon(
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: Theme.of(
                                        context,
                                      ).colorScheme.primary,
                                      padding: const EdgeInsets.symmetric(
                                        horizontal: 32,
                                        vertical: 16,
                                      ),
                                      shape: RoundedRectangleBorder(
                                        borderRadius: BorderRadius.circular(8),
                                      ),
                                    ),
                                    icon: const Icon(
                                      Icons.arrow_forward,
                                      color: Colors.white,
                                    ),
                                    label: const Text(
                                      'Finalizar',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 16,
                                      ),
                                    ),
                                    onPressed: () async {
                                      if (_formKey.currentState!.validate()) {
                                        try {
                                          await vm.salvarGrupo();
                                          if (context.mounted) {
                                            TopSnackBar.show(
                                              context,
                                              'Grupo de estudo gravado com sucesso!',
                                            );
                                            Navigator.of(context).pop();
                                          }
                                        } catch (e) {
                                          TopSnackBar.show(
                                            context,
                                            e.toString(),
                                            color: Colors.red[700],
                                          );
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

                // CROPPER
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
                            aspectRatio: vm.isCroppingAvatar ? 1 : 3,
                            withCircleUi: vm.isCroppingAvatar,
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
