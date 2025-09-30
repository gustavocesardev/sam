import 'package:flutter/material.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_viewmodel.dart';
import 'package:sam_app/presentation/widgets/cards/feed_post_card.dart';

class PostListView extends StatelessWidget {
  final FeedViewModel vm;
  final ScrollController controller;

  final int? idGrupoEstudo;
  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;

  const PostListView({
    super.key,
    required this.vm,
    required this.controller,
    required this.idAutor,
    required this.tipoAutorPublicacao,
    this.idGrupoEstudo,
  });

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      controller: controller,
      padding: const EdgeInsets.all(4),
      itemCount: vm.posts.length + (vm.isLoading || !vm.hasMore ? 1 : 0),
      itemBuilder: (context, index) {
        if (index == vm.posts.length) {
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
                padding: EdgeInsets.only(bottom: 25, top: 10),
                child: Text(
                  'Parece que você chegou ao fim',
                  style: TextStyle(color: Colors.white70, fontSize: 14),
                ),
              ),
            );
          }
        }

        final post = vm.posts[index];
        return FeedPostCard(
          key: ValueKey(post.id),
          idPublicacao: post.id,
          idGrupoEstudo: idGrupoEstudo,
          name: post.nome,
          cursoInfo: post.curso,
          content: post.texto,
          comments: post.comentarios,
          likes: post.curtidas,
          liked: post.curtido,
          avatarColor: Colors.primaries[index % Colors.primaries.length],
          imageHashes: post.imagens,
          idAutor: idAutor,
          tipoAutorPublicacao: tipoAutorPublicacao,
          avatarHash: post.avatarEncrypted,
        );
      },
    );
  }
}
