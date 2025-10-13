import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_grupo_viewmodel.dart';
import 'package:sam_app/presentation/widgets/list_view/post_list_view.dart';

class FeedGrupoPage extends StatefulWidget {
  final int idGrupoEstudo;
  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;

  const FeedGrupoPage({
    super.key,
    required this.idGrupoEstudo,
    required this.idAutor,
    required this.tipoAutorPublicacao,
  });

  @override
  State<FeedGrupoPage> createState() => _FeedGrupoPageState();
}

class _FeedGrupoPageState extends State<FeedGrupoPage> {
  late FeedGrupoViewmodel viewModel;
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    viewModel = FeedGrupoViewmodel(
      repo: FeedRepository(),
      idGrupoEstudo: widget.idGrupoEstudo,
    );

    WidgetsBinding.instance.addPostFrameCallback((_) {
      viewModel.loadInitial();
    });

    _setupScrollListener();
  }

  void _setupScrollListener() {
    _scrollController.addListener(() {
      if (_scrollController.position.pixels >=
              _scrollController.position.maxScrollExtent - 200 &&
          !viewModel.isLoading) {
        viewModel.loadMore();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider<FeedGrupoViewmodel>.value(
      value: viewModel,
      child: Consumer<FeedGrupoViewmodel>(
        builder: (context, vm, _) {
          return RefreshIndicator(
            onRefresh: () async => vm.loadInitial(),
            color: Theme.of(context).colorScheme.secondary,
            backgroundColor: Theme.of(context).scaffoldBackgroundColor,
            child: PostListView(
              feedKey: 'feedGrupo',
              vm: vm,
              controller: _scrollController,
              idGrupoEstudo: widget.idGrupoEstudo,
              idAutor: widget.idAutor,
              tipoAutorPublicacao: widget.tipoAutorPublicacao,
            ),
          );
        },
      ),
    );
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }
}
