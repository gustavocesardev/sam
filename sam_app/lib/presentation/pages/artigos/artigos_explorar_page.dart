import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/domain/viewmodels/artigo/artigos_explorar_viewmodel.dart';
import 'package:sam_app/presentation/widgets/input/field_rounded_icon.dart';
import 'package:sam_app/presentation/widgets/list_view/artigos_list_view.dart';

class ArtigosExplorarPage extends StatefulWidget {
  const ArtigosExplorarPage({super.key});

  @override
  State<ArtigosExplorarPage> createState() => _ArtigosExplorarPageState();
}

class _ArtigosExplorarPageState extends State<ArtigosExplorarPage> {
  late ArtigosExplorarViewmodel viewModel;
  final _scrollController = ScrollController();
  final _tituloController = TextEditingController();
  final _hashtagsController = TextEditingController();

  bool _showAdvancedFilters = false;

  @override
  void initState() {
    super.initState();
    viewModel = context.read<ArtigosExplorarViewmodel>();

    _tituloController.addListener(() {
      viewModel.setTitulo(_tituloController.text);
    });

    _hashtagsController.addListener(() {
      viewModel.setHashtags(_hashtagsController.text);
    });

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
    _tituloController.dispose();
    _hashtagsController.dispose();
    viewModel.resetExplorar(notify: false);
    super.dispose();
  }

  void _toggleAdvancedFilters() {
    setState(() {
      _showAdvancedFilters = !_showAdvancedFilters;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<ArtigosExplorarViewmodel>(
      builder: (context, vm, _) {
        return RefreshIndicator(
          onRefresh: () async => vm.loadInitial(),
          color: Theme.of(context).colorScheme.secondary,
          backgroundColor: Theme.of(context).scaffoldBackgroundColor,
          child: Column(
            children: [
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 16, 16, 0),
                child: Column(
                  children: [
                    FielRoundedIcon(
                      controller: _tituloController,
                      icon: Icons.search,
                      label: 'Pesquisar título',
                    ),
                    if (_showAdvancedFilters) ...[
                      const SizedBox(height: 16),
                      FielRoundedIcon(
                        controller: _hashtagsController,
                        icon: Icons.tag,
                        label: 'Hashtags',
                      ),
                    ],
                    Align(
                      alignment: Alignment.centerRight,
                      child: TextButton(
                        onPressed: _toggleAdvancedFilters,
                        child: Text(
                          _showAdvancedFilters
                              ? 'Ocultar pesquisa avançada'
                              : 'Pesquisa avançada',
                          style: TextStyle(
                            fontSize: 12,
                            color: Theme.of(context).colorScheme.secondary,
                            fontWeight: FontWeight.w400,
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),

              if (vm.isFiltering || vm.isLoadingInitial)
                const Padding(
                  padding: EdgeInsets.all(16),
                  child: Center(child: CircularProgressIndicator()),
                )
              else
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
