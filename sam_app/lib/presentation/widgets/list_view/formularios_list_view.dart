import 'package:flutter/material.dart';
import 'package:sam_app/domain/viewmodels/formulario/formularios_viewmodel.dart';
import 'package:sam_app/presentation/widgets/cards/form_card.dart';

class FormulariosListView extends StatelessWidget {
  final FormulariosViewmodel vm;
  final ScrollController controller;

  const FormulariosListView({
    super.key,
    required this.vm,
    required this.controller,
  });

  @override
  Widget build(BuildContext context) {
    if (vm.forms.isEmpty && !vm.isLoading) {
      return const Center(
        child: Text(
          'Nenhum formulário encontrado :(',
          style: TextStyle(color: Colors.white70, fontSize: 16),
        ),
      );
    }

    return ListView.builder(
      controller: controller,
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
        return FormCard(
          key: ValueKey(form.id),
          periodo: form.periodo,
          curso: form.curso,
          autor: form.nome,
          titulo: form.titulo,
          corFundo: Theme.of(context).colorScheme.primary,
        );
      },
    );
  }
}
