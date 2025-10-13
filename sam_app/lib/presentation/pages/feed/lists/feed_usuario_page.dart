import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_usuario_viewmodel.dart';
import 'package:sam_app/presentation/widgets/list_view/post_list_view.dart';

class FeedUsuarioPage extends StatefulWidget {
  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;

  const FeedUsuarioPage({
    super.key,
    required this.idAutor,
    required this.tipoAutorPublicacao,
  });

  @override
  State<FeedUsuarioPage> createState() => _FeedUsuarioPageState();
}

class _FeedUsuarioPageState extends State<FeedUsuarioPage> {
  final ScrollController _scrollController = ScrollController();
  bool _hasLoaded = false;

  void _setupScrollListener(FeedUsuarioViewmodel vm) {
    _scrollController.addListener(() {
      if (_scrollController.position.pixels >=
              _scrollController.position.maxScrollExtent - 200 &&
          !vm.isLoading &&
          vm.hasMore) {
        vm.loadMore();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<FeedUsuarioViewmodel>(
      builder: (context, vm, _) {
        if (!_hasLoaded) {
          WidgetsBinding.instance.addPostFrameCallback((_) {
            vm.loadInitial();
            _setupScrollListener(vm);
          });
          _hasLoaded = true;
        }

        if (vm.isLoading && vm.posts.isEmpty) {
          return const Center(child: CircularProgressIndicator());
        }

        return RefreshIndicator(
          onRefresh: () async => vm.loadInitial(),
          color: Theme.of(context).colorScheme.secondary,
          backgroundColor: Theme.of(context).scaffoldBackgroundColor,
          child: PostListView(
            feedKey: 'feedUsuario',
            vm: vm,
            controller: _scrollController,
            idAutor: widget.idAutor,
            tipoAutorPublicacao: widget.tipoAutorPublicacao,
          ),
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