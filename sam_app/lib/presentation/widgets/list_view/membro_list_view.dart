// lib/presentation/widgets/list_view/membro_list_view.dart
import 'package:flutter/material.dart';
import 'package:sam_app/data/models/membro_model.dart';
import 'package:sam_app/presentation/widgets/cards/membro_card.dart';

class MembroListView extends StatelessWidget {
  final List<MembroModel> membros;

  const MembroListView({super.key, required this.membros});

  @override
  Widget build(BuildContext context) {
    if (membros.isEmpty) {
      return Padding(
        padding: const EdgeInsets.all(16.0),
        child: const Center(child: Text('Nenhum membro encontrado :(')),
      );
    }

    return ListView.builder(
      padding: const EdgeInsets.all(4),
      itemCount: membros.length,
      itemBuilder: (context, index) {
        final membro = membros[index];
        return MembroCard(
          membro: membro,
          avatarColor: Colors.primaries[index % Colors.primaries.length],
        );
      },
    );
  }
}