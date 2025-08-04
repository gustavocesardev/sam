import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/data/enums/tipo_formulario_enum.dart';
import 'package:sam_app/data/models/curso_model.dart';
import 'package:sam_app/data/repositories/curso_repository.dart';
import 'package:sam_app/domain/viewmodels/formulario/formularios_explorar_viewmodel.dart';
import 'package:sam_app/presentation/widgets/input/custom_dropdown.dart';
import 'package:sam_app/presentation/widgets/input/field_rounded_icon.dart';
import 'package:sam_app/presentation/widgets/list_view/formularios_list_view.dart';

class FormulariosExplorarPage extends StatefulWidget {
  const FormulariosExplorarPage({super.key});

  @override
  State<FormulariosExplorarPage> createState() =>
      _FormulariosExplorarPageState();
}

class _FormulariosExplorarPageState extends State<FormulariosExplorarPage> {
  late FormulariosExplorarViewmodel viewModel;
  final _scrollController = ScrollController();
  final _tituloController = TextEditingController();

  List<CursoModel> _cursos = [];
  bool _isCursosLoading = true;

  @override
  void initState() {
    super.initState();
    viewModel = context.read<FormulariosExplorarViewmodel>();

    _tituloController.addListener(() {
      viewModel.setTitulo(_tituloController.text);
    });

    _loadCursos();

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

  Future<void> _loadCursos() async {
    final cursos = await CursoRepository().getCursosPorInstituicao(
      idInstituicao: 4,
    );
    if (mounted) {
      setState(() {
        _cursos = cursos;
        _isCursosLoading = false;
      });
    }
  }

  @override
  void dispose() {
    _scrollController.dispose();
    _tituloController.dispose();
    viewModel.resetExplorar(notify: false);
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<FormulariosExplorarViewmodel>(
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
                    ),

                    if (vm.showAdvancedFilters) ...[
                      const SizedBox(height: 16),
                      CustomDropdown<String>(
                        valorSelecionado: vm.selectedTipo,
                        onChanged: vm.setSelectedTipo,
                        label: 'Tipos',
                        itens: [
                          const DropdownMenuItem(
                            value: 'Todos',
                            child: Text('Todos'),
                          ),
                          ...TipoFormularioEnum.values.map(
                            (tipo) => DropdownMenuItem(
                              value: tipo.codigo,
                              child: Text(tipo.descricao),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      _isCursosLoading
                          ? const CircularProgressIndicator()
                          : CustomDropdown<int>(
                              valorSelecionado: vm.selectedIdCurso,
                              label: 'Curso',
                              itens: [
                                const DropdownMenuItem(
                                  value: 0,
                                  child: Text('Todos'),
                                ),
                                ..._cursos.map(
                                  (c) => DropdownMenuItem(
                                    value: c.id,
                                    child: Text(c.nomeCurso),
                                  ),
                                ),
                              ],
                              onChanged: vm.setSelectedCurso,
                            ),
                    ],

                    Align(
                      alignment: Alignment.centerRight,
                      child: TextButton(
                        onPressed: vm.toggleAdvancedFilters,
                        child: Text(
                          vm.showAdvancedFilters
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
