import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_curso_viewmodel.dart';
import 'package:sam_app/presentation/widgets/list_view/post_list_view.dart';

class FeedCursoPage extends StatefulWidget {
  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;

  const FeedCursoPage({
    super.key,
    required this.idAutor,
    required this.tipoAutorPublicacao
  });

  @override
  State<FeedCursoPage> createState() => _FeedCursoPageState();
}

class _FeedCursoPageState extends State<FeedCursoPage> {
  late FeedCursoViewmodel viewModel;
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    viewModel = context.read<FeedCursoViewmodel>();

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

    if (viewModel.posts.isEmpty) {
      return const Center(child: Text('Nenhuma publicação encontrada :('));
    }

    return Consumer<FeedCursoViewmodel>(
      builder: (context, vm, _) {
        return RefreshIndicator(
          onRefresh: () async => vm.loadInitial(),
          color: Theme.of(context).colorScheme.secondary,
          backgroundColor: Theme.of(context).scaffoldBackgroundColor,
          child: PostListView(
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
