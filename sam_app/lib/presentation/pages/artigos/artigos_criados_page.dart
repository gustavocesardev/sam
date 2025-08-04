import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/domain/viewmodels/artigo/artigos_criados_viewmodel.dart';
import 'package:sam_app/presentation/widgets/list_view/artigos_list_view.dart';

class ArtigosCriadosPage extends StatefulWidget {
  const ArtigosCriadosPage({super.key});

  @override
  State<ArtigosCriadosPage> createState() => _ArtigosCriadosPage();
}

class _ArtigosCriadosPage extends State<ArtigosCriadosPage> {
  late ArtigosCriadosViewmodel viewModel;
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    viewModel = context.read<ArtigosCriadosViewmodel>();

    WidgetsBinding.instance.addPostFrameCallback((_) {
      viewModel.loadInitial();
    });

    _scrollController.addListener(() {
      if (_scrollController.position.pixels >=
              _scrollController.position.maxScrollExtent - 200 &&
          !viewModel.isLoading) {
        viewModel.loadMore();
      }
    });
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<ArtigosCriadosViewmodel>(
      builder: (context, vm, _) {
        return RefreshIndicator(
          onRefresh: () async => vm.loadInitial(),
          color: Theme.of(context).colorScheme.secondary,
          backgroundColor: Theme.of(context).scaffoldBackgroundColor,
          child: Column(
            children: [
              if (vm.isLoadingInitial)
                const Padding(
                  padding: EdgeInsets.all(16),
                  child: Center(child: CircularProgressIndicator()),
                )
              else
                SizedBox(height: 12,),
                Expanded(
                  child: ArtigosListView(vm: vm, controller: _scrollController),
                ),
            ],
          ),
        );
      },
    );
  }
}
