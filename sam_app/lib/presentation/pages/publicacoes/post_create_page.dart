import 'dart:io';

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/domain/viewmodels/publicacao/post_create_viewmodel.dart';
import 'package:sam_app/presentation/widgets/snack/top_snack_bar.dart';

class PostCreatePage extends StatelessWidget {
  final int idAutor;
  final TipoAutorPublicacao tipoAutor;
  final int? idPublicacaoVinculada;

  const PostCreatePage({
    super.key,
    required this.idAutor,
    required this.tipoAutor,
    this.idPublicacaoVinculada,
  });

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => PostCreateViewmodel(
        idAutor: idAutor,
        tipoAutor: tipoAutor,
        idPublicacaoVinculada: idPublicacaoVinculada,
      ),
      child: Consumer<PostCreateViewmodel>(
        builder: (context, vm, child) {
          return Scaffold(
            appBar: AppBar(
              title: const Text('Nova Publicação'),
              actions: [
                TextButton(
                  onPressed: vm.canPublish
                      ? () async {
                          try {
                            await vm.publish(context);
                            if (context.mounted) {
                              TopSnackBar.show(context, 'Publicação efetuada com sucesso!');
                              Navigator.pop(context);
                            }
                          } catch (_) {
                            if (context.mounted) {
                              TopSnackBar.show(context, 'Erro ao publicar. Tente novamente.');
                            }
                          }
                        }
                      : null,
                  child: const Text('Publicar', style: TextStyle(color: Colors.white)),
                ),
              ],
            ),
            body: Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                children: [
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      CircleAvatar(
                        backgroundColor: Theme.of(context).colorScheme.secondary,
                        radius: 18,
                        backgroundImage: vm.userImageUrl != null
                            ? NetworkImage(vm.userImageUrl!)
                            : null,
                        child: vm.userImageUrl == null ? const Icon(Icons.person) : null,
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: TextField(
                          controller: vm.textController,
                          maxLength: 155,
                          maxLines: null,
                          decoration: const InputDecoration(
                            hintText: 'O que você está estudando hoje?',
                            border: InputBorder.none,
                            counterText: '',
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  if (vm.images.isNotEmpty)
                    SizedBox(
                      height: 80,
                      child: ListView.builder(
                        scrollDirection: Axis.horizontal,
                        itemCount: vm.images.length,
                        itemBuilder: (context, index) {
                          return Stack(
                            children: [
                              Container(
                                margin: const EdgeInsets.symmetric(horizontal: 6),
                                child: Image.file(
                                  File(vm.images[index].path),
                                  width: 85,
                                  height: 85,
                                  fit: BoxFit.cover,
                                ),
                              ),
                              Positioned(
                                right: 2,
                                top: 2,
                                child: GestureDetector(
                                  onTap: () => vm.removeImage(index),
                                  child: Container(
                                    decoration: const BoxDecoration(
                                      color: Colors.black54,
                                      shape: BoxShape.circle,
                                    ),
                                    child: const Icon(Icons.close, color: Colors.white, size: 18),
                                  ),
                                ),
                              ),
                            ],
                          );
                        },
                      ),
                    ),
                  const Spacer(),
                  Row(
                    children: [
                      IconButton(
                        icon: const Icon(Icons.image_outlined),
                        onPressed: () => vm.pickImage(context),
                        tooltip: 'Adicionar imagem',
                      ),
                      const Spacer(),
                    ],
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}
