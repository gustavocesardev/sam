import 'package:flutter/material.dart';
import 'package:sam_app/domain/viewmodels/artigo/artigos_viewmodel.dart';
import 'package:sam_app/presentation/widgets/cards/artigo_card.dart';

class ArtigosListView extends StatelessWidget {
  final ArtigosViewmodel vm;
  final ScrollController controller;

  const ArtigosListView({
    super.key,
    required this.vm,
    required this.controller,
  });

  @override
  Widget build(BuildContext context) {
    if (vm.artigos.isEmpty && !vm.isLoadingInitial) {
      return const Center(
        child: Text(
          'Nenhum artigo encontrado :(',
          style: TextStyle(color: Colors.white70, fontSize: 16),
        ),
      );
    }

    return ListView.builder(
      controller: controller,
      itemCount: vm.artigos.length + (vm.isLoading || !vm.hasMore ? 1 : 0),
      padding: EdgeInsets.fromLTRB(8, 0, 8, 0),
      itemBuilder: (context, index) {
        if (index == vm.artigos.length || vm.isLoadingInitial) {
          if (vm.isLoadingMore) {
            return const Center(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: CircularProgressIndicator(),
              ),
            );
          } else {
            return const Center(
              child: Padding(
                padding: EdgeInsets.only(
                  bottom: 40,
                  top: 10,
                  left: 15,
                  right: 15,
                ),
                child: Column(
                  children: [
                    SizedBox(height: 12),
                    Text(
                      'Parece que você chegou ao fim',
                      style: TextStyle(color: Colors.white70, fontSize: 14),
                    ),
                  ],
                ),
              ),
            );
          }
        }

        final artigo = vm.artigos[index];
        return ArtigoCard(
          dataPublicacao: artigo.publicadoEm,
          titulo: artigo.titulo,
          autor: "${artigo.nome} - ${artigo.anoCurso}",
          descricao: artigo.conteudo,
        );
      },
    );
  }
}
