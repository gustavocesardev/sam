import 'package:flutter/material.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupo_estudo_view_model.dart';
import 'package:sam_app/presentation/widgets/snack/top_snack_bar.dart';

class GrupoEstudoSobrePage extends StatelessWidget {
  final GrupoEstudoViewModel vm;
  final int idUsuario;
  final int? idMembro;

  const GrupoEstudoSobrePage({
    super.key,
    required this.vm,
    required this.idUsuario,
    this.idMembro,
  });

  @override
  Widget build(BuildContext context) {
    final grupo = vm.grupo;

    return Scaffold(
      appBar: AppBar(title: const Text("Sobre o grupo")),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: ListView(
          children: [
            const Text(
              "Informações",
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            Row(
              children: const [
                Icon(Icons.people, size: 20),
                SizedBox(width: 8),
                Expanded(child: Text("Apenas os membros podem postar.")),
              ],
            ),
            const SizedBox(height: 24),
            Row(
              children: const [
                Icon(Icons.public, size: 20),
                SizedBox(width: 8),
                Expanded(
                  child: Text.rich(
                    TextSpan(
                      children: [
                        TextSpan(
                          text:
                              "Todos os grupos de estudo ficam visíveis ao público. ",
                          style: TextStyle(fontWeight: FontWeight.bold),
                        ),
                        TextSpan(
                          text: "Qualquer pessoa pode ingressar nesse grupo.",
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),
            Row(
              children: [
                const Icon(Icons.calendar_today, size: 20),
                const SizedBox(width: 8),
                Text("Criado em ${grupo.criacao}"),
              ],
            ),
            const SizedBox(height: 24),
            Row(
              children: [
                const Icon(Icons.person_2_outlined, size: 20),
                const SizedBox(width: 8),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [Text("Por ${grupo.nomeUsuario}")],
                ),
              ],
            ),
            const SizedBox(height: 24),
            if (grupo.descricao.isNotEmpty) ...[
              const Divider(),
              const SizedBox(height: 24),
              const Text(
                "Descrição",
                style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8),
              Text(grupo.descricao),
            ],

            const SizedBox(height: 32),

            // Botão Sair do grupo
            if (idMembro != null && idUsuario != grupo.idUsuario)
              ElevatedButton.icon(
                onPressed: () async {
                  final sucesso = await vm.sairDoGrupo(
                    idUsuario: idUsuario,
                    idMembro: idMembro!,
                  );

                  if (sucesso) {
                    TopSnackBar.show(
                      context,
                      'Você saiu do grupo de estudo!',
                      color: Colors.orange[800],
                    );
                    Navigator.pop(context, true);
                  } else {
                    TopSnackBar.show(
                      context,
                      'Falha ao sair do grupo. Tente novamente',
                      color: Colors.red.shade700,
                    );
                  }
                },
                icon: const Icon(Icons.exit_to_app, color: Colors.white),
                label:
                    const Text('Sair do grupo', style: TextStyle(color: Colors.white)),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red.shade700,
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }
}
