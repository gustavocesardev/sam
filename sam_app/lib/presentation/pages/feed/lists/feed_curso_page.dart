import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_curso_viewmodel.dart';
import 'package:sam_app/presentation/widgets/post/post_list_view.dart';

class FeedCursoPage extends StatefulWidget {
  const FeedCursoPage({super.key});

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
    return Consumer<FeedCursoViewmodel>(
      builder: (context, vm, _) {
        return RefreshIndicator(
          onRefresh: () async => vm.loadInitial(),
          color: Theme.of(context).colorScheme.secondary,
          backgroundColor: Theme.of(context).scaffoldBackgroundColor,
          child: PostListView(vm: vm, controller: _scrollController),
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
