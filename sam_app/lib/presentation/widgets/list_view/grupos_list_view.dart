import 'package:flutter/material.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupos_estudo_viewmodel.dart';
import 'package:sam_app/presentation/widgets/cards/grupo_card.dart';

class GruposListView extends StatelessWidget {
  final GruposEstudoViewmodel vm;
  final ScrollController controller;

  const GruposListView({
    super.key,
    required this.vm,
    required this.controller
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
        return GrupoCard(
          key: ValueKey(grupo.id),
          iconPath: grupo.imagem,
          nome: grupo.nomeGrupo,
          membros: grupo.qtdeMembros,
          tags: grupo.hashtags,
        );
      },
    );
  }
}
