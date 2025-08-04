import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/domain/viewmodels/formulario/formularios_criados_viewmodel.dart';
import 'package:sam_app/presentation/widgets/list_view/formularios_list_view.dart';

class FormulariosCriadosPage extends StatefulWidget {
  const FormulariosCriadosPage({super.key});

  @override
  State<FormulariosCriadosPage> createState() => _FormulariosCriadosPageState();
}

class _FormulariosCriadosPageState extends State<FormulariosCriadosPage> {
  late FormulariosCriadosViewmodel viewModel;
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    viewModel = context.read<FormulariosCriadosViewmodel>();

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
    return Consumer<FormulariosCriadosViewmodel>(
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
                Expanded(
                  child: FormulariosListView(
                    vm: vm,
                    controller: _scrollController,
                  ),
                ),
            ],
          ),
        );
      },
    );
  }
}
