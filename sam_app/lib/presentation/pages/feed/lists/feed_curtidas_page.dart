import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_curtidas_viewmodel.dart';
import 'package:sam_app/presentation/widgets/list_view/post_list_view.dart';

class FeedCurtidasPage extends StatefulWidget {
  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;

  const FeedCurtidasPage({
    super.key,
    required this.idAutor,
    required this.tipoAutorPublicacao
  });

  @override
  State<FeedCurtidasPage> createState() => _FeedCurtidasPageState();
}

class _FeedCurtidasPageState extends State<FeedCurtidasPage> {
  late FeedCurtidasViewmodel viewModel;
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    viewModel = context.read<FeedCurtidasViewmodel>();

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

    return Consumer<FeedCurtidasViewmodel>(
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
