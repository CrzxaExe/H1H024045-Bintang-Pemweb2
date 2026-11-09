package repository

import (
	"latihan-fiber-api/internal/config"
	"latihan-fiber-api/internal/model"
)

type ProgramStudiDenganJumlahMahasiswa struct {
	model.ProgramStudi
	JumlahMahasiswa int64 `json:"jumlah_mahasiswa"`
}

func AmbilProgramStudiDenganJumlahMahasiswa() ([]ProgramStudiDenganJumlahMahasiswa, error) {
	var programStudi []model.ProgramStudi
	if err := config.DB.Order("nama ASC").Find(&programStudi).Error; err != nil {
		return nil, err
	}

	type jumlahMahasiswaPerProgram struct {
		ProgramStudiID uint
		Jumlah         int64
	}
	var jumlahPerProgram []jumlahMahasiswaPerProgram
	if err := config.DB.Model(&model.Mahasiswa{}).
		Select("program_studi_id, COUNT(*) AS jumlah").
		Group("program_studi_id").
		Scan(&jumlahPerProgram).Error; err != nil {
		return nil, err
	}

	jumlahMahasiswa := make(map[uint]int64, len(jumlahPerProgram))
	for _, hasil := range jumlahPerProgram {
		jumlahMahasiswa[hasil.ProgramStudiID] = hasil.Jumlah
	}

	hasil := make([]ProgramStudiDenganJumlahMahasiswa, 0, len(programStudi))
	for _, program := range programStudi {
		hasil = append(hasil, ProgramStudiDenganJumlahMahasiswa{
			ProgramStudi:    program,
			JumlahMahasiswa: jumlahMahasiswa[program.ID],
		})
	}
	return hasil, nil
}
