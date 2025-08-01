import 'package:flutter/material.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_viewmodel.dart';
import 'package:sam_app/presentation/widgets/post/post_card.dart';

class PostListView extends StatelessWidget {
  final FeedViewModel vm;
  final ScrollController controller;

  const PostListView({
    super.key, 
    required this.vm,
    required this.controller,
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
                padding: EdgeInsets.only(bottom: 40),
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
          name: post.nome,
          cursoInfo: post.curso,
          content: post.texto,
          comments: post.comentarios,
          likes: post.curtidas,
          avatarColor: Colors.primaries[index % Colors.primaries.length],
          imageHashes: post.imagens,
          avatarHash: post.avatarEncrypted,
        );
      },
    );
  }
}
