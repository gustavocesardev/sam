import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_geral_viewmodel.dart';
import 'package:sam_app/presentation/widgets/post/post_list_view.dart';

class FeedGeralPage extends StatefulWidget {
  const FeedGeralPage({super.key});

  @override
  State<FeedGeralPage> createState() => _FeedGeralPageState();
}

class _FeedGeralPageState extends State<FeedGeralPage> {
  late FeedGeralViewmodel viewModel;
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    viewModel = context.read<FeedGeralViewmodel>();

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
    return Consumer<FeedGeralViewmodel>(
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
