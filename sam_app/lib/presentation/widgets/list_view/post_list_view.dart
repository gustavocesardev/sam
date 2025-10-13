import 'package:flutter/material.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_viewmodel.dart';
import 'package:sam_app/presentation/widgets/cards/feed_post_card.dart';

class PostListView extends StatefulWidget {
  final FeedViewModel vm;
  final ScrollController controller;
  final String feedKey;

  final int? idGrupoEstudo;
  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;

  final ScrollPhysics physics;

  const PostListView({
    super.key,
    required this.vm,
    required this.controller,
    required this.idAutor,
    required this.tipoAutorPublicacao,
    required this.feedKey,
    this.physics = const AlwaysScrollableScrollPhysics(),
    this.idGrupoEstudo,
  });

  @override
  State<PostListView> createState() => _PostListViewState();
}

class _PostListViewState extends State<PostListView>
    with AutomaticKeepAliveClientMixin {
  @override
  bool get wantKeepAlive => true;

  @override
  Widget build(BuildContext context) {
    super.build(context);

    final vm = widget.vm;

    if (vm.isLoading && vm.posts.isEmpty) {
      return const Center(child: CircularProgressIndicator());
    }

    if (vm.posts.isEmpty) {
      return const Center(
        child: Text(
          'Nenhuma publicação encontrada :(',
          style: TextStyle(color: Colors.white70, fontSize: 16),
          textAlign: TextAlign.center,
        ),
      );
    }

    return ListView.builder(
      key: PageStorageKey(widget.feedKey),
      physics: widget.physics,
      controller: widget.controller,
      padding: const EdgeInsets.all(4),
      itemCount: vm.posts.length + (vm.isLoading || !vm.hasMore ? 1 : 0),
      itemBuilder: (context, index) {
        if (index == vm.posts.length) {
          if (vm.isLoading) {
            return const Padding(
              padding: EdgeInsets.all(16),
              child: Center(child: CircularProgressIndicator()),
            );
          } else {
            return const Padding(
              padding: EdgeInsets.only(bottom: 25, top: 10),
              child: Center(
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
          idUsuario: post.idUsuario,
          idGrupoEstudo: widget.idGrupoEstudo,
          name: post.nome,
          cursoInfo: post.curso,
          content: post.texto,
          comments: post.comentarios,
          likes: post.curtidas,
          liked: post.curtido,
          avatarColor: Colors.primaries[index % Colors.primaries.length],
          imageHashes: post.imagens,
          idAutor: widget.idAutor,
          tipoAutorPublicacao: widget.tipoAutorPublicacao,
          avatarHash: post.avatarEncrypted,
          onDelete: () {
            setState(() {
              vm.posts.removeAt(index);
            });
          },
          moreActions: (widget.tipoAutorPublicacao.atributo == TipoAutorPublicacao.membro.atributo)
            ? (post.idMembro == widget.idAutor)
            : (post.idUsuario == widget.idAutor)
        );
      },
    );
  }
}
