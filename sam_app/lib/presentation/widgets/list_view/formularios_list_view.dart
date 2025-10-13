import 'package:flutter/material.dart';
import 'package:sam_app/domain/viewmodels/formulario/formularios_viewmodel.dart';
import 'package:sam_app/presentation/pages/formularios/formulario_form_page.dart';
import 'package:sam_app/presentation/pages/formularios/formulario_page.dart';
import 'package:sam_app/presentation/widgets/cards/form_card.dart';
import 'package:sam_app/shared/utils/storage_utils.dart';

class FormulariosListView extends StatelessWidget {
  final FormulariosViewmodel vm;
  final ScrollController controller;
  final bool isCriado;

  final ScrollPhysics physics;

  const FormulariosListView({
    super.key,
    required this.vm,
    required this.controller,
    this.isCriado = false,
    this.physics = const AlwaysScrollableScrollPhysics(),
  });

  @override
  Widget build(BuildContext context) {
    if (vm.forms.isEmpty && !vm.isLoading) {
      return const Center(child: Text('Nenhum formulário encontrado :('));
    }

    return ListView.builder(
      controller: controller,
      physics: physics,
      itemCount: vm.forms.length + (vm.isLoading || !vm.hasMore ? 1 : 0),
      itemBuilder: (context, index) {
        if (index == vm.forms.length) {
          if (vm.isLoadingMore) {
            return const Center(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: CircularProgressIndicator(),
              ),
            );
          } else {
            return const Center(
              child: Padding(
                padding: EdgeInsets.only(
                  bottom: 40,
                  top: 10,
                  left: 15,
                  right: 15,
                ),
                child: Column(
                  children: [
                    Divider(color: Colors.white12, height: 1),
                    SizedBox(height: 12),
                    Text(
                      'Parece que você chegou ao fim',
                      style: TextStyle(color: Colors.white70, fontSize: 14),
                    ),
                  ],
                ),
              ),
            );
          }
        }

        final form = vm.forms[index];

        return GestureDetector(
          onTap: () async {
            if (isCriado) {
              final userId = await StorageUtils.getUserId();
              if (userId == null) return;

              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => FormularioFormPage(
                    idUsuario: userId,
                    idFormulario: form.id,
                  ),
                ),
              ).then((value) {
                if (value == true) {
                  vm.loadInitial();
                }
              });
            } else {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => FormularioPage(idFormulario: form.id),
                ),
              );
            }
          },
          child: FormCard(
            key: ValueKey(form.id),
            periodo: form.periodo,
            curso: form.curso,
            autor: form.nome,
            titulo: form.titulo,
            corFundo: Theme.of(context).colorScheme.primary,
          ),
        );
      },
    );
  }
}
