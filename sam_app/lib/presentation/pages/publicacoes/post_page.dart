import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/data/repositories/publicacao/publicacao_repository.dart';
import 'package:sam_app/data/services/publicacao/publicacao_service.dart';
import 'package:sam_app/domain/viewmodels/publicacao/post_viewmodel.dart';
import 'package:sam_app/presentation/pages/publicacoes/post_create_page.dart';
import 'package:sam_app/presentation/widgets/app_bar/simple_app_bar.dart';
import 'package:sam_app/presentation/widgets/cards/feed_post_card.dart';

class PostPage extends StatelessWidget {
  final int idPublicacao;
  final int? idGrupoEstudo;

  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;

  const PostPage({
    super.key,
    required this.idPublicacao,
    required this.idGrupoEstudo,
    required this.idAutor,
    required this.tipoAutorPublicacao
  });

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider<PostViewmodel>(
      create: (_) {
        final feedRepo = FeedRepository();
        final publicacaoRepo = PublicacaoRepository(PublicacaoService());
        final vm = PostViewmodel(
          feedRepo: feedRepo,
          publicacaoRepo: publicacaoRepo,
          idPublicacao: idPublicacao,
          idGrupoEstudo: idGrupoEstudo,
          idAutor: idAutor,
          tipoAutorPublicacao: tipoAutorPublicacao
        );
        vm.loadPublicacao();
        vm.loadInitial();
        return vm;
      },
      child: _PostPageBody(),
    );
  }
}

class _PostPageBody extends StatefulWidget {
  @override
  State<_PostPageBody> createState() => _PostPageBodyState();
}

class _PostPageBodyState extends State<_PostPageBody> {
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();

    _scrollController.addListener(() async {
      final vm = Provider.of<PostViewmodel>(context, listen: false);

      if (_scrollController.position.pixels >=
              _scrollController.position.maxScrollExtent - 200 &&
          !vm.isLoading &&
          vm.hasMore) {
        await vm.loadMore();
      }
    });
  }

  void _onComentar(PostViewmodel vm) {

    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => PostCreatePage(
          idAutor: vm.idAutor,
          tipoAutor: vm.tipoAutorPublicacao,
          idPublicacaoVinculada: vm.idPublicacao,
        )
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<PostViewmodel>(
      builder: (context, vm, _) {
        return Scaffold(
          appBar: SimpleAppBar(textAppBar: 'Publicação'),
          body: vm.isLoadingDetalhe
              ? const Center(child: CircularProgressIndicator())
              : RefreshIndicator(
                  onRefresh: () async {
                    await vm.loadPublicacao();
                    await vm.loadInitial();
                  },
                  color: Theme.of(context).colorScheme.secondary,
                  backgroundColor: Theme.of(context).scaffoldBackgroundColor,
                  child: ListView.builder(
                    controller: _scrollController,
                    physics: const AlwaysScrollableScrollPhysics(),
                    padding: const EdgeInsets.all(4),
                    itemCount: 1 + vm.posts.length + 1,
                    itemBuilder: (context, index) {
                      if (index == 0) {
                        return Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            FeedPostCard(
                              key: ValueKey(vm.publicacao.id),
                              idPublicacao: vm.publicacao.id,
                              idGrupoEstudo: vm.idGrupoEstudo,
                              name: vm.publicacao.nome,
                              cursoInfo: vm.publicacao.curso,
                              content: vm.publicacao.texto,
                              comments: vm.publicacao.comentarios,
                              likes: vm.publicacao.curtidas,
                              liked: vm.publicacao.curtido,
                              avatarColor: Colors.primaries[0],
                              imageHashes: vm.publicacao.imagens,
                              avatarHash: vm.publicacao.avatarEncrypted,
                              idAutor: vm.idAutor,
                              tipoAutorPublicacao: vm.tipoAutorPublicacao,
                              openDetails: false, 
                            ),
                            if (vm.posts.isNotEmpty)
                              const Padding(
                                padding: EdgeInsets.symmetric(horizontal: 10),
                                child: Row(
                                  mainAxisSize: MainAxisSize.min,
                                  children: [
                                    Text(
                                      'Comentários mais recentes',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 14,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            const SizedBox(height: 14),
                          ],
                        );
                      }

                      if (index == 1 + vm.posts.length) {
                        if (vm.isLoading) {
                          return const Center(
                            child: Padding(
                              padding: EdgeInsets.all(16),
                              child: CircularProgressIndicator(),
                            ),
                          );
                        } else {
                          return const SizedBox.shrink();
                        }
                      }

                      final post = vm.posts[index - 1];
                      return FeedPostCard(
                        key: ValueKey(post.id),
                        idPublicacao: post.id,
                        idGrupoEstudo: vm.idGrupoEstudo,
                        name: post.nome,
                        cursoInfo: post.curso,
                        content: post.texto,
                        comments: post.comentarios,
                        likes: post.curtidas,
                        liked: post.curtido,
                        avatarColor: Colors
                            .primaries[(index - 1) % Colors.primaries.length],
                        imageHashes: post.imagens,
                        idAutor: vm.idAutor,
                             tipoAutorPublicacao: vm.tipoAutorPublicacao,
                        avatarHash: post.avatarEncrypted,
                      );
                    },
                  ),
                ),
          floatingActionButton: Transform.translate(
            offset: const Offset(0, -20),
            child: FloatingActionButton(
              onPressed: () => _onComentar(vm),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(32),
              ),
              backgroundColor: Colors.blue[200],
              child: const Icon(Icons.add_comment, size: 25),
            ),
          ),
          floatingActionButtonLocation:
              FloatingActionButtonLocation.centerFloat,
        );
      },
    );
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }
}
