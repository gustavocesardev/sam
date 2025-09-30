import 'package:flutter/material.dart';

class InstituicoesPage extends StatelessWidget {
  const InstituicoesPage({super.key});

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      backgroundColor: Color(0xFF0C0F1D),
      body: Center(
        child: Text('Selecionar instituicoes!', style: TextStyle(color: Colors.white)),
      ),
    );
  }
}