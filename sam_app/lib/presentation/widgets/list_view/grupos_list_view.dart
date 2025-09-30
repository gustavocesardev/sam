import 'package:flutter/material.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupos_estudo_viewmodel.dart';
import 'package:sam_app/presentation/pages/grupos/grupo_estudo_form_page.dart';
import 'package:sam_app/presentation/pages/grupos/grupo_estudo_page.dart';
import 'package:sam_app/presentation/widgets/cards/grupo_card.dart';
import 'package:sam_app/shared/utils/storage_utils.dart';

class GruposListView extends StatelessWidget {
  final GruposEstudoViewmodel vm;
  final ScrollController controller;
  final bool isCriado;

  const GruposListView({
    super.key,
    required this.vm,
    required this.controller,
    this.isCriado = false,
  });

  @override
  Widget build(BuildContext context) {
    if (vm.grupos.isEmpty && !vm.isLoading) {
      return const Center(
        child: Text(
          'Nenhum grupo encontrado :(',
          style: TextStyle(color: Colors.white70, fontSize: 16),
        ),
      );
    }

    return ListView.builder(
      controller: controller,
      padding: const EdgeInsets.only(top: 12),
      itemCount: vm.grupos.length + (vm.isLoading || !vm.hasMore ? 1 : 0),
      itemBuilder: (context, index) {
        if (index == vm.grupos.length) {
          if (vm.isLoading) {
            return const Center(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: CircularProgressIndicator(),
              ),
            );
          } else {
            return const Center(
              child: Padding(
                padding: EdgeInsets.only(bottom: 40),
                child: Text(
                  'Parece que você chegou ao fim',
                  style: TextStyle(color: Colors.white70, fontSize: 14),
                ),
              ),
            );
          }
        }

        final grupo = vm.grupos[index];
        return GestureDetector(
          onTap: () async {
            final userId = await StorageUtils.getUserId();
            if (userId == null) return;

            if (isCriado) {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => GrupoEstudoFormPage(
                    idUsuario: userId,
                    idGrupoEstudo: grupo.id,
                  ),
                ),
              ).then((value) {
                if (value == true) {
                  vm.loadInitial();
                }
              });
            } else {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => GrupoEstudoPage(
                    idUsuario: userId,
                    idGrupoEstudo: grupo.id,
                    idMembro: grupo.idMembro,
                  ),
                ),
              ).then((_) {
                vm.loadInitial();
              });
            }
          },
          child: GrupoCard(
            key: ValueKey(grupo.id),
            iconPath: grupo.imagem,
            nome: grupo.nomeGrupo,
            membros: grupo.qtdeMembros,
            tags: grupo.hashtags,
          ),
        );
      },
    );
  }
}
