import 'package:flutter/material.dart';
import 'package:sam_app/domain/viewmodels/artigo/artigos_viewmodel.dart';
import 'package:sam_app/presentation/pages/artigos/artigo_form_page.dart';
import 'package:sam_app/presentation/widgets/cards/artigo_card.dart';
import 'package:sam_app/shared/utils/storage_utils.dart';

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
    if (vm.artigos.isEmpty && !vm.isLoading) {
      return const Center(
        child: Text(
          'Nenhum artigo encontrado :(',
          style: TextStyle(color: Colors.white70, fontSize: 16),
        ),
      );
    }

    return ListView.builder(
      controller: controller,
      itemCount: vm.artigos.length + (vm.isLoadingMore || (vm.artigos.isNotEmpty && !vm.hasMore) ? 1 : 0),
      padding: const EdgeInsets.symmetric(horizontal: 8),
      itemBuilder: (context, index) {
        if (index == vm.artigos.length) {
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
                padding: EdgeInsets.only(bottom: 40, top: 10, left: 15, right: 15),
                child: Column(
                  children: [
                    Divider(color: Colors.white12, height: 1),
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

        return GestureDetector(
          onTap: () async {
            final userId = await StorageUtils.getUserId();
            if (userId == null) return;

            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (_) => ArtigoFormPage(
                  idUsuario: userId,
                  idArtigo: artigo.id,
                ),
              ),
            ).then((value) {
              if (value == true) {
                vm.loadInitial();
              }
            });
          },
          child: ArtigoCard(
            key: ValueKey(artigo.id),
            dataPublicacao: artigo.publicadoEm,
            titulo: artigo.titulo,
            autor: "${artigo.nome} - ${artigo.anoCurso}",
            descricao: vm.quillContentToPlainText(artigo.conteudo),
          )
        );
      },
    );
  }
}