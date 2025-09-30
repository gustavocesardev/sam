import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupos_ingressados_viewmodel.dart';
import 'package:sam_app/presentation/widgets/list_view/grupos_list_view.dart';

class GruposIngressadosPage extends StatefulWidget {
  const GruposIngressadosPage({super.key});

  @override
  State<GruposIngressadosPage> createState() => _GruposIngressadosPageState();
}

class _GruposIngressadosPageState extends State<GruposIngressadosPage> {
  late GruposIngressadosViewmodel viewModel;
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    viewModel = context.read<GruposIngressadosViewmodel>();

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
    return Consumer<GruposIngressadosViewmodel>(
      builder: (context, vm, _) {
        return RefreshIndicator(
          onRefresh: () async => vm.loadInitial(),
          color: Theme.of(context).colorScheme.secondary,
          backgroundColor: Theme.of(context).scaffoldBackgroundColor,
          child: GruposListView(vm: vm, controller: _scrollController),
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
