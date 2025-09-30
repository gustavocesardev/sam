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
      return const Center(
        child: Padding(
          padding: EdgeInsets.all(16),
          child: Text(
            "Nenhum membro encontrado.",
            style: TextStyle(color: Colors.white70),
          ),
        ),
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